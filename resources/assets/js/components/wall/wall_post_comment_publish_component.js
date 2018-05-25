import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Wall_post_comment_publish_component extends React.Component {
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
            <div className="border-top  mt-3 pt-3 ">
                <div className="input-group mb-3 ">
                    <input type="text" className="form-control" placeholder="留言..." aria-label="留言..." aria-describedby="basic-addon2" />
                    <div className="input-group-append">
                        <button className="btn btn-outline-secondary" type="button">送出</button>
                    </div>
                </div>
            </div>
            
        )
    }
}