const worker = require('./worker');
const rabbitmqq = require('./rabbitmqq');


const rabbitTransportService = new rabbitmqq('amqp://guest:guest@localhost:5672');


const subscribeConf = {
    exchangeName: "screenshot.make",
    queueName: "make",
    callback: (res) => {
        console.log(res);
    }
}

rabbitTransportService.subscribeOnMessages(subscribeConf);


const sendConf = {
    exchangeName: "screenshot.make",
    queueName: "make",
}



setInterval(()=>{
    const message = Math.floor(Math.random() * 1000);
    rabbitTransportService.send({...sendConf, message});
},1500)


console.log('Subscribed!');