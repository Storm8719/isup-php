const amqp = require('amqplib/callback_api');
const Queue = require('./queue');

class RabbitMQTransporter {

    constructor(amqpUrl) {
        this.initialized = false;
        this.connection = null;
        this.channel = null;
        this.assertedExchanges = new Set();
        this.assertedQueues = new Set();
        this.bindings = new Set();
        this.sendMessagesQueue = new Queue();
        this.init(amqpUrl);
    }

    dequeueMessagesToSend() {
        if (this.sendMessagesQueue.length > 0) {
            const {exchangeName, exchangeOptions, listenQueueName, queueOptions, message} = this.sendMessagesQueue.dequeue();
            this.send({exchangeName, exchangeOptions, listenQueueName, queueOptions, message});
            this.dequeueMessagesToSend();
        }
    }

    init(amqpUrl) {

        const onClosedConnectionEventsHandler = () => {
            this.initialized = false;
            console.log('Connection closed... Waiting for initialization...');
            this.init(amqpUrl);
        }

        const reinitAfterDelay = () => {
            setTimeout(() => {
                this.init(amqpUrl);
            }, 1000);
        }


        amqp.connect(amqpUrl, (error0, connection) => {

            if (error0) {
                console.log('Failed to connect. Try to reconnect in 1s')
                reinitAfterDelay();
                return false;
            }

            this.connection = connection;
            connection.createChannel((error1, channel) => {

                this.channel = channel;
                this.channel.on('close', onClosedConnectionEventsHandler);
                this.channel.on('error', onClosedConnectionEventsHandler);

                if (error1) {
                    console.log('Channel not created. Try to reconnect in 1s')
                    reinitAfterDelay();
                    return false;
                }
                this.initialized = true;
                console.log('Connection success');
                this.dequeueMessagesToSend();
            });
        });

    }


    assertAction({exchangeName, exchangeOptions = {}, queueName, queueOptions = {}}) {

        if (!this.assertedExchanges.has(exchangeName)) {
            console.log(`Asserting exchange `, exchangeName);
            this.channel.assertExchange(exchangeName, "fanout", exchangeOptions);
            this.assertedExchanges.add(exchangeName);
        }

        if (!this.assertedQueues.has(queueName)) {
            console.log(`Asserting queues `, queueName);
            this.channel.assertQueue(queueName, queueOptions);
            this.assertedQueues.add(queueName);
        }

        if (!this.bindings.has(`${exchangeName}-${queueName}`)) {
            console.log(`Asserting binding `, `${exchangeName}-${queueName}`);
            this.channel.bindQueue(queueName, exchangeName, "");
            this.bindings.add(`${exchangeName}-${queueName}`);
        }

    }


    send({exchangeName, exchangeOptions = {}, queueName, queueOptions = {}, message}) {
        if (this.initialized) {
            queueName = `${exchangeName}.${queueName}`;
            this.assertAction({exchangeName, exchangeOptions, queueName, queueOptions});
            this.channel.publish(exchangeName, queueName, Buffer.from(JSON.stringify(message)));
        } else {
            this.sendMessagesQueue.enqueue({exchangeName, exchangeOptions, queueName, queueOptions, message});
        }
    }


    subscribeOnMessages({exchangeName, exchangeOptions = {}, queueName, queueOptions = {}, callback}) {
        if (this.initialized) {
            queueName = `${exchangeName}.${queueName}`;
            this.assertAction({exchangeName, exchangeOptions, queueName, queueOptions});
            this.channel.consume(queueName, function (msg) {
                callback(JSON.parse(msg.content.toString()));
            }, {
                noAck: true
            })

        } else {
            setTimeout(() => {
                this.subscribeOnMessages({exchangeName, exchangeOptions, queueName, queueOptions, callback});
            }, 100);
        }
    }

    stop() {
        if (this.initialized) {
            this.connection.close();
            return true;
        }
    }
}

module.exports = RabbitMQTransporter;