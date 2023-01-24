const worker = require('./worker');
const rabbitmqq = require('./rabbitmqq');


const rabbitTransportService = new rabbitmqq('amqp://guest:guest@localhost:5672');

const checker = new worker();

const checkAndScreenHandler = ({id, url, imgName = null}) => {

    imgName = imgName ? imgName : `image-${id}`;

    checker.makeScreenshot(id, url, imgName).then((res)=>{
        rabbitTransportService.send('check-and-screen-results', res);
    })
}

rabbitTransportService.subscribeOnMessages('check-and-screen', checkAndScreenHandler);