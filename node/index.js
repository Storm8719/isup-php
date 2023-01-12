const puppeteer = require('puppeteer');

const makeScreenshot = async (url, imgName, browser) => {

    const page = await browser.newPage();
    await page.setViewport({
        width: 1920,
        height: 1080,
        deviceScaleFactor: 1,
    })

    // await page.goto(url, {waitUntil: 'networkidle0', timeout: 0});
    // console.time('FirstWay'+imgName);
    const response = await page.goto(url, {timeout: 0});
    // const navigationPromise = page.waitForNavigation({ waitUntil: ['networkidle2'] });
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
        path: './' + imgName + '.png',
        type: 'png',
        fullPage: true
    })

    await page.close();
    return {...js_result_from_page, ...responseInfo};
}

(async () => {
    const browser = await puppeteer.launch({ headless: false });
    const actions = [];

    const listUrls = [
        'https://example.com',
        'https://www.php.net'
    ];

    let i = 0;
    listUrls.forEach((el) => {
        i++;
        actions.push(makeScreenshot(el, 'page-' + i, browser).then((result) => {
            // console.log(result);
            return result;
        }));
    })

    const result = await Promise.all(actions);
    console.log(result);


    await browser.close();
})();