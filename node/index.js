const puppeteer = require('puppeteer');

const makeScreenshot = async (url, imgName, browser) => {

    const page = await browser.newPage();
    await page.setViewport({
        width: 1920,
        height: 1080,
        deviceScaleFactor: 1,
    })

    // await page.goto(url, {waitUntil: 'networkidle0', timeout: 0});
    await page.goto(url, {waitUntil: 'load', timeout: 0});
    const ee = await page.waitForResponse(response => response.status() === 200);
    console.log(ee);

    const resultjs = await page.evaluate(() => {
        return {
            title:document.title,
            description: document.querySelector('meta[name="description"]').content,

        };
    });

    console.log(resultjs);

    await page.screenshot({
        path: './' + imgName + '.png',
        type: 'png',
        fullPage: true
    })

    console.log(imgName + ' done');

    await page.close();
}

(async () => {
    const browser = await puppeteer.launch({ headless: false });
    const actions = [];

    const listUrls = [
        'http://example.com/',
        'https://example.com/www'
    ];

    let i = 0;
    listUrls.forEach((el) => {
        i++;
        actions.push(makeScreenshot(el, 'page-' + i, browser).then(() => {
            console.log(i);
            return i;
        }));
    })

    const result = await Promise.all(actions);
    console.log(result);
    await browser.close();
})();