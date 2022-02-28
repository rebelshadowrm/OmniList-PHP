document.addEventListener("DOMContentLoaded", getMedia);







function getMedia() {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    const id = urlParams.get('id');

    if(id) {
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

            //define media object
            const media = data.data.Media;

            //Media properties
            let bannerImage = media.bannerImage;
            let coverImage = media.coverImage.large;
            let description = media.description;
            description = description.replaceAll('<br><br>', '</p><p class="synapsis__text">')
            let title = media.title.romaji;
            
            //character array
            let characters = media.characters.nodes;

            //Set title for each info object
            let averageScore = new Object();
            averageScore.title = "Average Score";
            averageScore.media = media.averageScore;

            let chapters = new Object();
            chapters.title = "Chapters";
            chapters.media = media.chapters;

            let startDate = new Object();
            startDate.title = "Start Date";
            startDate.media = `${media.startDate.month}/${media.startDate.day}/${media.startDate.year}`;
            
            let endDate = new Object();
            endDate.title = "End Date";
            let removeNull = `${media.endDate.month}/${media.endDate.day}/${media.endDate.year}`;
            endDate.media = removeNull.replaceAll("null", "-");

            let status = new Object();
            status.title = "Status";
            status.media = media.status;
            
            let genres = new Object();
            genres.title = "Genres";
            genres.media = media.genres.join(', ');

            //create info array
            let infoArray = [
                averageScore,
                chapters,
                startDate,
                endDate,
                status,
                genres
            ];

            // Generate Main page
            const mediaTemplate = document.querySelector("#mediaTemplate");
            const mediaContainer = document.querySelector(".mediaContainer");

            let mediaClone = mediaTemplate.content.cloneNode(true);

            let banner = mediaClone.querySelector(".bannerImg");
            if (bannerImage === null) {
                banner.style.display='none';
            } else {
            banner.src=bannerImage;
            }
            let addMediaImg = mediaClone.querySelector(".addMedia__Img");
            addMediaImg.src=coverImage;
            let synapsisTitle = mediaClone.querySelector(".synapsis__title");
            synapsisTitle.innerText = title;
            let synapsisText = mediaClone.querySelector(".synapsis__text");
            synapsisText.innerHTML = description;

            mediaContainer.appendChild(mediaClone);

            let user_id = document.querySelector('.addMedia__Button').dataset.id;
            let addButton = document.querySelector('.addMedia__Button');
            const mangaLookupUrl = `index.php?route=api&type=manga&manga_id=${id}&user_id=${user_id}`;

            fetch(mangaLookupUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-type':'application/json'
                }})
                .then( async (res) => {
                    let data = await res.json();

                    if(res.status === 200) {
                        $user_id = addButton.dataset.id;
                        addButton.remove();
                        let rating = data?.manga_rating ?? '';
                        let status = data?.user_status ?? '';
                        let progress = data?.progress ?? '';
                        addStatusRatingProgress(status, rating, progress, user_id);
                    }
                })
                .catch((err) => {
                    console.log(err);
                });

            
            //generate character list
            characters.forEach((elem) => {
                const characterTemplate = document.querySelector("#characterTemplate");
                const characterContainer = document.querySelector(".characters");

                let characterClone = characterTemplate.content.cloneNode(true);

                let characterImg = characterClone.querySelector(".character__Img");
                characterImg.src = elem.image.medium;
                let characterName = characterClone.querySelector(".character__Name");
                characterName.innerText = elem.name.full;

                characterContainer.appendChild(characterClone);
            });
            
            //generate info list
            infoArray.forEach((elem) => {
                const informationTemplate = document.querySelector("#informationTemplate");
                const informationContainer = document.querySelector(".informationAside");
                let informationClone = informationTemplate.content.cloneNode(true);
                let informationTitle = informationClone.querySelector(".information__title");
                informationTitle.innerText = elem.title;
                let informationDetails = informationClone.querySelector(".information__details");
                informationDetails.innerText = elem.media;
                informationContainer.appendChild(informationClone);
            });


            document.querySelector('.addMedia__Button').onclick = function addManga(e) {
                e.preventDefault();
                let user_id = e.composedPath()[0].dataset.id;
                const addMangaUrl = 'index.php?route=api&type=manga&create';

                fetch(addMangaUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-type':'application/json'
                    },
                    body:JSON.stringify({user_id:user_id,
                                         manga_id:id})
                })
                .then( async (res) => {
                    let data = await res.json();

                    if(res.status === 201) {
                        let user_id = addButton.dataset.id;
                        addButton.remove();
                        addStatusRatingProgress(2,null,0,user_id);
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
            }
            

        }

        function handleError(error) {
            console.error(error);
        }
    }
    
}


function addStatusRatingProgress(status = '', rating = '', progress = '', user_id) {

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    const manga_id = urlParams.get('id');
    
    let addMedia = document.querySelector('.addMedia');

    //Todo: very very very magical, need to replace
    let chapters = document.querySelector('.informationAside > .information:nth-child(3) > .information__details').innerText;
    let max = chapters === '' ? 9999 : chapters;
    chapters = chapters === '' ? '&#8212;' : chapters;

    let userAddedHtml = `
    <div data-id="${user_id}" class="addMedia__info">
        <label for="status" class="addMedia__label">Status: </label>
        <select id="status" name="status" class="addMedia__select_status">
            <option value="2">reading</option>
            <option value="4">on-hold</option>
            <option value="5">dropped</option>
            <option value="6">completed</option>
        </select>
        <div class="addMedia__rating">
        <label for="rating" class="addMedia__label">Rating: </label>
        <select id="rating" name="rating" class="addMedia__select_rating" >
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        </select>
        </div>
        <div class="addMedia__progress">
        <label for="progress" class="addMedia__progress">Progress: 
        <input name="progress" id="progress" class="addMedia__progress_num" type="number" min="0" max="${max}">
        <span>/ ${chapters}</span>
        </label>
        </div>
    </div>
    `;
    addMedia.innerHTML += userAddedHtml;


    let selectStatus = document.querySelector('.addMedia__select_status');
    let selectRating = document.querySelector('.addMedia__select_rating');
    let selectProgress = document.querySelector('.addMedia__progress_num');

    selectStatus.value = status;
    selectRating.value = rating;
    selectProgress.value = progress;


    selectStatus.onchange = function() {
        const updateStatusUrl = 'index.php?route=api&type=manga';

        let status_id = selectStatus.value;

        fetch(updateStatusUrl, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-type':'application/json'
            },
            body:JSON.stringify({status_id:status_id,
                                user_id:user_id,
                                manga_id:manga_id})
        })
        .then( async (res) => {
            let data = await res.json();
            
            if(res.status === 200) {

            }
        })
        // .catch((err) => {
        //     //console.log(err);
        // });
    }

    selectRating.onchange = function() {
        const updateRatingUrl = 'index.php?route=api&type=manga';

        let rating = selectRating.value;

        fetch(updateRatingUrl, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-type':'application/json'
            },
            body:JSON.stringify({rating:rating,
                                user_id:user_id,
                                manga_id:manga_id})
        })
        .then( async (res) => {
            let data = await res.json();

            if(res.status === 200) {
                
            }
        })
        // .catch((err) => {
        //     console.log(err);
        // });
    }


    selectProgress.onchange = function() {
        const updateProgressUrl = 'index.php?route=api&type=manga';
        let chapters_read = selectProgress.value;

        fetch(updateProgressUrl, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-type':'application/json'
            },
            body:JSON.stringify({chapters_read:chapters_read,
                                user_id:user_id,
                                manga_id:manga_id})
        })
        .then( async (res) => {
            let data = await res.json();

            if(res.status === 200) {
                
            }
        })
        // .catch((err) => {
        //     console.log(err);
        // });
    }
            
}