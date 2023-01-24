const amqp = require('amqplib/callback_api');

class RabbitMQQ{

    initialized = false;
    connection;
    channel;

    constructor(amqpUrl) {
        this.init(amqpUrl)
    }

    init(amqpUrl){
        amqp.connect(amqpUrl, (error0, connection) => {
            if (error0) {
                console.log('Failed to connect. Try to reconnect in 1s')
                setTimeout(()=> {
                    this.init(amqpUrl);
                },1000);
                return false;
            }
            this.connection = connection;
            connection.createChannel((error1, channel) => {

                this.channel = channel;
                this.channel.on('close', () => {
                    this.initialized = false;
                    console.log('Connection closed... Waiting for initialization...');
                    this.init(amqpUrl);
                });

                if (error1) {
                    console.log('Channel not created. Try to reconnect in 1s')
                    setTimeout(()=> {
                        this.init(amqpUrl);
                    },1000);
                    return false;
                }
                this.initialized = true;
                console.log('Connection success');
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
            setTimeout(()=>{
                this.send(toQueueName, message);
            }, 100);
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