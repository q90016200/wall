
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
	page:1
}


axios.get("/api/wall/posts/latest",{
	params: {
      t: getPostLatestState.getTime,
      page:getPostLatestState.page
    }
}).then(function(response){
	console.log(response);
})

