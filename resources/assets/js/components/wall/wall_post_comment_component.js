import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Wall_post_comment_component extends React.Component {
	constructor(props) {
        super(props);
        this.state = {
            items:props.items
        }

        // console.log(this.state.items);

        // this.state.items.map(item => (
        //     console.log(item)
        // ));
    }

    render(){

        return(
        	<div></div>
        )
    }
}