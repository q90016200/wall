
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Wall_post_publish from '../components/wall/wall_publish.js';
import Wall_post_component from '../components/wall/wall_post_component.js';

// require('../components/wall/wall_publish.js');

window.onload = function(){
    autosize(document.querySelectorAll('textarea'));
}


ReactDOM.render(
	<Wall_post_publish username={user.name} />,
	document.getElementById('wall_publish')
);
