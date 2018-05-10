
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Wall_post_publish from '../components/wall/wall_publish.js';

// require('../components/wall/wall_publish.js');

window.onload = function(){
    autosize(document.querySelectorAll('textarea'));
}


ReactDOM.render(
	<Wall_post_publish name="guest" />,
	document.getElementById('wall_publish_div')
);