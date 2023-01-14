const puppeteer = require('puppeteer');
const Queue = require('./queue');


class worker
{
    initialized = false;
    pagesOpened = 0;
    pagesLimit = 3;
    browser;
    queue;


    constructor() {
        this.queue = new Queue();
        puppeteer.launch({ headless: false }).then((browser) => {
            this.browser = browser;
            this.initialized = true;
            setInterval(()=>{
                this.resolveQueue();
            },1)
        });
    }

    resolveQueue = () => {
        if (this.pagesOpened < this.pagesLimit && this.queue.length) {
            this.execute(this.queue.dequeue());
        }
    }

    makeScreenshot = (id, url, imgName) => {
        return new Promise((resolve, reject) => {
            const resultCall = (res) => {
                resolve(res)
            }
            this.queue.enqueue({id, url, imgName, callback:resultCall});
        });
    }


    execute = async ({id, url, imgName, callback}) => {
        this.pagesOpened++;
        const page = await this.browser.newPage();

        await page.setViewport({
            width: 1920,
            height: 1080,
            deviceScaleFactor: 1,
        })
        await page.setDefaultTimeout(60000);

        const response = await page.goto(url, {waitUntil:"load"});

        const responseInfo = {
            timing:response.timing(),
            status: response.status(),
            headers: response.headers()
        }

        const js_result_from_page = await page.evaluate(() => {
            const meta_desc = document.querySelector('meta[name="description"]');
            const description = (meta_desc !== null && meta_desc.content) ? meta_desc.content : null;

            return {
                title:document.title,
                description: description,
            };
        });

        await page.screenshot({
            path: './' + imgName + '.jpeg',
            type: 'jpeg',
            quality: 50,
        })
        await page.close();
        this.pagesOpened--;

        this.resolveQueue();
        // console.log(`DONE ${imgName}`);

        callback({id, url, imgName, ...js_result_from_page, ...responseInfo});
    }
}

(() => {
    const checker = new worker();

    for (let i = 2; i !== 0; i--) {
        checker.makeScreenshot(i ,'http://isup/main/timeout?time='+i, 'page-'+i).then((res)=>{
            console.log(res);
        })
        // console.log(i)
    }
})();