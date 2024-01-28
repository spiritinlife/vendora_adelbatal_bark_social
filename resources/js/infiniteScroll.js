window.onscroll = function () {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 200) { // You might need to adjust the '100'
        loadMoreBarks();
    }
};

let isLoading = false;
let nextPage = 2;
const userId = document.body.getAttribute('data-user-id');
const loading = document.getElementById('loading');
function loadMoreBarks() {
    // Prevent multiple simultaneous requests
    if (isLoading) {
        return;
    }

    isLoading = true;
    document.getElementById('loading').style.display = 'block';

    fetch(`/user/${userId}/barks?feedType=${getCurrentFeedType()}&page=${nextPage}`)
        .then(response => response.text())
        .then(data => {
            if (data) {
                document.getElementById('feed-container').innerHTML += data;
                nextPage++;
                isLoading = false;
                loading.style.display = 'none';
            } else {
                window.onscroll = null;
                loading.innerText = 'No more barks to load.';
            }
        })
        .catch(error => {
            loading.style.innerText = 'Error loading barks.';
        })
        .finally(() => {
            console.log('arakse dld posa barks thes');
        });
}

function getCurrentFeedType() {
    const urlParams = new URLSearchParams(window.location.search);
    const feedType = urlParams.get('feed');

    return feedType || 'home';
}
