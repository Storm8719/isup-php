const amqp = require('amqplib/callback_api');
const Queue = require('./queue');

class RabbitMQQ{

    initialized = false;
    connection;
    channel;
    sendMessagesQueue;

    constructor(amqpUrl) {
        this.init(amqpUrl);
        let i = 0;
        //TODO Fix bug with missing messages
        setInterval(()=>{
            this.send('check-and-screen-results', i);
            i++;
        }, 1000);
    }

    deququeMessagesToSend(){
        if(this.sendMessagesQueue.length > 0){
            const {toQueueName, message} = this.sendMessagesQueue.dequeue();
            this.send(toQueueName, message);
            this.deququeMessagesToSend();
        }
    }

    init(amqpUrl){

        this.sendMessagesQueue = new Queue();

        const onClosedConnectionEventsHandler = () => {
            this.initialized = false;
            console.log('Connection closed... Waiting for initialization...');
            this.init(amqpUrl);
        }

        const reinitAfterDelay = () => {
            setTimeout(()=> {
                this.init(amqpUrl);
            },1000);
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
                this.deququeMessagesToSend();
            });
        });

    }

    send(toQueueName, message){
        if(this.initialized){
            this.channel.assertQueue(toQueueName, {
                durable: false
            });
            console.log("[x] sended to queue "+ toQueueName + " message:" + JSON.stringify(message));
            this.channel.publish('', toQueueName, Buffer.from(JSON.stringify(message)));
        }else{
            this.sendMessagesQueue.enqueue({toQueueName, message});
        }
    }

    subscribeOnMessages(listenQueueName, callback){
        if(this.initialized){
            this.channel.assertQueue(listenQueueName, {
                durable: false
            });

            this.channel.consume(listenQueueName, function(msg) {
                callback(JSON.parse(msg.content.toString()));
            }, {
                noAck: true
            });
        }else{
            setTimeout(()=>{
                this.subscribeOnMessages(listenQueueName, callback);
            }, 100);
        }
    }

    stop(){
        if(this.initialized){
            this.connection.close();
            return true;
        }
    }
}

module.exports = RabbitMQQ;