
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Wall_post_publish_component from '../components/wall/wall_post_publish_component.js';
import Wall_post_component from '../components/wall/wall_post_component.js';

// require('../components/wall/wall_publish.js');


// 載入 發布 dom
ReactDOM.render(
	<Wall_post_publish_component username={user.name} />,
	document.getElementById('wall_publish')
);
// 載入 post dom
ReactDOM.render(
	<Wall_post_component />,
	document.getElementById('wall_posts')
);

