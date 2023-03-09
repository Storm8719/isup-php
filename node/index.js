// const worker = require('./worker');
const rabbitmqq = require('./rabbitmqq');


const rabbitTransportService = new rabbitmqq('amqp://guest:guest@localhost:5672');


const subscribeConf = {
    exchangeName: "screenshot.make",
    listenQueueName: "make",
    callback: (res) => {
        console.log(res);
    }
}

rabbitTransportService.subscribeOnMessages(subscribeConf);


const sendConf = {
    exchangeName: "screenshot.make",
    listenQueueName: "make",
    message: "Test!"
}

rabbitTransportService.send(sendConf);
