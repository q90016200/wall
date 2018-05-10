
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Wall_post_publish extends React.Component {
    constructor(props) {
        super(props);
        this.state = {photo_status:false};
    }

    

    render(){

        if (this.state.photo_status){
            photo_div = <div></div>;  
        }

        return(
            <div className="d-flex justify-content-between bd-highlight mb-3">
                <Wall_post_publish_user name={this.props.name} />
            
                <div></div>

                <div className="">
                    <a href="" >
                        <span className="oi oi-image"></span>
                    </a>
                    <input type="file" className="visible" />
                </div>
            </div>
        );
    }
    

}

function Wall_post_publish_user(props) {props
    return (
        <div>
            <span className="oi oi-person"></span>
            <span className="ml-1"> {props.name} </span>    
        </div>
    );
}