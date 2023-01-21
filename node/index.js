const worker = require('./worker');
const MicroMQ = require('micromq');


const app = new MicroMQ({
    name: 'node-checker-with-screenshots',
    rabbit: {
        url: 'amqp://guest:guest@localhost:5672',
    },
});


const checker = new worker();

app.get('/check-website', (req, res) => {

    checker.makeScreenshot(1 ,'http://isup/main/timeout?time='+1, 'page-'+1).then((res)=>{
        console.log(res);
    })

    res.json({status:"ok"});


});


(() => {
    const checker = new worker();

    for (let i = 2; i !== 0; i--) {
        checker.makeScreenshot(i ,'http://isup/main/timeout?time='+i, 'page-'+i).then((res)=>{
            console.log(res);
        })
        // console.log(i)
    }
})();