
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Wall_post_publish_component from '../components/wall/wall_post_publish_component.js';
import Wall_post_component from '../components/wall/wall_post_component.js';

// require('../components/wall/wall_publish.js');

// 
// window.onload = function(){
//     autosize(document.querySelectorAll('textarea'));
// }


// 載入 發布 dom
ReactDOM.render(
	<Wall_post_publish_component username={user.name} />,
	document.getElementById('wall_publish')
);

autosize(document.getElementById("publish_textarea"));


let getPostLatestState = {
	getTime:new Date().valueOf(),
	page:1,
	status:true,
	firstGet:true
}

getPostLatest();

function getPostLatest(){

	if(getPostLatestState.status){

		getPostLatestState.status = false;

		// 載入最新5篇
		axios.get("/api/wall/posts/latest",{
			params: {
		      t: getPostLatestState.getTime,
		      page:getPostLatestState.page
		    }
		}).then(function(response){
			console.log(response.data);

			ReactDOM.render(
				<Wall_post_component items={response.data.posts_data} append="after" />,
				document.getElementById('wall_posts')
			);

			if (getPostLatestState.page < response.data.total_page) {
                getPostLatestState.status = true;
            } 

			setTimeout(function () {
                scroll_auto_load_post();
            }, 150);
		});
	}
	
}

function scroll_auto_load_post(){
	window.addEventListener('scroll', function (e) {
        if (getPostLatestState.status) {

            var wh = window.innerHeight;;
            var wstop = document.documentElement && document.documentElement.scrollTop || document.body.scrollTop;

            var el_wall_content = document.getElementsByClassName('container')[0];

            if (wh + wstop > el_wall_content.clientHeight * 0.85) {
                getPostLatestState.page ++;
                getPostLatest();
            }
        }
    });
}

