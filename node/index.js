const worker = require('./worker');


(() => {
    const checker = new worker();

    for (let i = 2; i !== 0; i--) {
        checker.makeScreenshot(i ,'http://isup/main/timeout?time='+i, 'page-'+i).then((res)=>{
            console.log(res);
        })
        // console.log(i)
    }
})();