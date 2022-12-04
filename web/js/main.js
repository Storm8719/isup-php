// fetch('/main/get-websites-list').then((response) => console.log(response));


// fetch('/main/get-websites-list')
//     .then((response)=>{
//         console.log(response)
//     })
const searchInput = document.getElementById('liveSearch');
const resultBox = document.getElementById('resultBox');

const getAllWebsites = async () => {
    let response = await fetch('/main/get-websites-list');
    if (response.ok) {
        return await response.json();
    } else {
        console.log("Error HTTP: " + response.status);
    }
}

const initLiveSearch = async () => {
    searchInput.removeEventListener('focus', initLiveSearch);
    const websites = await getAllWebsites();
    const fuse = new Fuse(websites, {
        keys: ['url']
    });
    ['keyup'].forEach((eventType) => {
        searchInput.addEventListener(eventType, () => {
            renderLiveSearchResults(fuse.search(searchInput.value));
        });
    })
}

searchInput.addEventListener('focus', initLiveSearch)

const renderLiveSearchResults = (results) => {

    const fragment = document.createDocumentFragment();

    results.forEach((item) => {
        const listItem = document.createElement('a');
        listItem.innerText = item.item.url;
        listItem.href = '#';
        fragment.appendChild(listItem);
    })

    resultBox.innerHTML = '';
    resultBox.appendChild(fragment);
}
