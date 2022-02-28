document.addEventListener("DOMContentLoaded", getHome);


function getHome() {


        var query = `
            query ($id: Int) {
                Media(type: MANGA, id: $id) {
                    title {
                    romaji
                    }
                    description
                    bannerImage
                    coverImage {
                    large
                    }
                    characters {
                    nodes {
                        image {
                        medium
                        }
                        name {
                        full
                        }
                    }
                    }
                    genres
                    chapters
                    status
                    startDate {
                        year
                        month
                        day
                    }
                    endDate {
                    year
                    month
                    day
                    }
                    averageScore
                }
            }
            `;
        var variables = {
            id: id
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


            

        }

        function handleError(error) {
            console.error(error);
        }
        
    }
    