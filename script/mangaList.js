import Tabulator from 'https://unpkg.com/tabulator-tables@4.9.3/dist/js/tabulator.es2015.min.js';
const user_id = document.querySelector('#manga-table').dataset.user_id;



document.addEventListener("DOMContentLoaded", function() {
    fetch(`index.php?route=api&type=manga&user_id=${user_id}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-type':'application/json'
        }})
        .then( async (res) => {
            let data = await res.json();

            if(res.status === 200) {
                let t = document.querySelector('#manga-table');
                let html = `<h1 class="manga__list_title">${data[0].user_alias}'s List</h1>`;
                t.insertAdjacentHTML('beforebegin', html);
            }
        })
        .catch((err) => {
            console.log(err);
        });
});



var variableMax= function(cell, value, parameters){
    var maxValue = cell.getRow().getData().chapters;
    maxValue = maxValue === null ? 9999 : maxValue;

    return value <= maxValue;
}


var table = new Tabulator("#manga-table", {
    ajaxURL:`index.php?route=api&type=manga&user_id=${user_id}`,
    layout:"fitColumns",
    autoResize: true,
    resizableColumns:false,
    resizableRows:false,
    responsiveLayout:"collapse",
    maxHeight: "100%",
    cellEdited:function(cell){
        const updateUrl = 'index.php?route=api&type=manga';
        var manga_id = cell.getData().manga_id;
        var field = cell.getField();
        var value = cell.getValue();

        fetch(updateUrl, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-type':'application/json'
            },
            body:JSON.stringify({[field]:value,
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

    },
    groupBy:"user_status",
    initialSort:[
        {column:"manga_rating", dir:"desc"},
    ],
    columns: [
        {title:"Manga Title", field:"title", sorter:"string", widthGrow:3,
         formatter:function(cell) {
            var status = cell.getRow().getData().manga_status;
            var manga_id = cell.getRow().getData().manga_id;
            var href = `index.php?route=manga&id=${manga_id}`;
            return `<a href="${href}" class="list__link">${cell.getValue()}</a>  &#8212;  ${status}`
        }},
        {title:"Rating", field:"manga_rating", width:90, hozAlign:"center", editor:"select", sorter:"number", editorParams:{
            values:[1,2,3,4,5,6,7,8,9,10]
        }},
        {title:"Progress", field:"progress", width:100, hozAlign:"center", editor:"number", editorParams:function(cell) {
        var chapters = cell.getRow().getData().chapters
        return { min:0, max:chapters, step:1 };
        }, validator:["min:0", {type:variableMax, parameters:{}}]
        , sorter:"number", formatter:function(cell) {
            var chapters = cell.getRow().getData().chapters;
            chapters = chapters === null ? '&#8212;' : chapters;
            return `${cell.getValue()} / ${chapters}`
        }},
        {formatter:"buttonCross", width:40, hozAlign:"center", cellClick:function(e, row){
            var row = row.getRow();
            const deleteMangaUrl  = "index.php?route=api&type=manga";
            let manga_id = row.getData().manga_id;
            row.delete()
            .then(function() {
                fetch(deleteMangaUrl, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-type':'application/json'
                    },
                    body:JSON.stringify({user_id:user_id,
                                         manga_id:manga_id})
                })
                .then( async (res) => {
                    let data = await res.json();

                    if(res.status === 201) {

                    }
                })
                .catch((err) => {
                    console.log(err);
                });
           })
            .catch(function(err){
                console.log(err);
            });
        }}

    ],
});