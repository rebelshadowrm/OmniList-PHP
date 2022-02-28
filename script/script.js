
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }
function test() {
// Here we define our query as a multi-line string
// Storing it in a separate .graphql/.gql file is also possible

// for (let i = 1092; i <= 1435; i++) {
//    setTimeout(() => {

var query = `
query ($page: Int, $perPage: Int) {
    Page(page: $page, perPage: $perPage) {
        pageInfo {
            total
            currentPage
            lastPage
            hasNextPage
        }
        media(type: MANGA, sort: ID) {
            id
            genres
            chapters
            status
            title {
                romaji
            }
        }
    }
}
`;
//pages 1436
var variables = {
    page: 1436,
    perPage: 45
};

// Define the config we'll need for our Api request
var url = 'https://graphql.anilist.co',
    options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            query: query,
            variables: variables
        })
    };

var fillUrl = 'index.php?route=api&type=dbFill';

// Make the HTTP Api request
fetch(url, options).then(handleResponse)
                .then(handleData)
                .catch(handleError);

function handleResponse(response) {
    return response.json().then(function (json) {
        return response.ok ? json : Promise.reject(json);
    });
}

function handleData(data) {
     data.data.Page.media.forEach(e => {
         let id = e.id;
         let title = e.title.romaji;
         let chapters = e.chapters;
         let status = e.status;

         let genres = e.genres;

         fetch(fillUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json', 
            },
            body: JSON.stringify({
                manga_id: id,
                title: title,
                chapters: chapters,
                status: status,
                genres: genres
            })
        })
        .then((res) => res.json())
        .then((json) => {
            console.log(json.message);
        });
     });
}

function handleError(error) {
    alert('Error, check console');
    console.error(error);
}
// }, (i - 1091)*31000);
// }
}
