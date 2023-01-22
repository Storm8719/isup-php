const amqp = require('amqplib/callback_api');

class RabbitMQQ{

    initialized = false;
    connection;
    channel;

    constructor(amqpUrl) {
        amqp.connect(amqpUrl, (error0, connection) => {
            if (error0) {
                throw error0;
            }
            this.connection = connection;
            connection.createChannel((error1, channel) => {

                this.channel = channel;

                if (error1) {
                    throw error0;
                }
                this.initialized = true;
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

    subscribeOnMessages(messagesToListenQueueName, callback){
        if(this.initialized){
            this.channel.assertQueue(messagesToListenQueueName, {
                durable: false
            });

            this.channel.consume(messagesToListenQueueName, function(msg) {
                callback(JSON.parse(msg.content.toString()));
            }, {
                noAck: true
            });
        }else{
            setTimeout(()=>{
                this.subscribeOnMessages(messagesToListenQueueName, callback);
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