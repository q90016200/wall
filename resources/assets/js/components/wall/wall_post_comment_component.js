import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Wall_post_comment_component extends React.Component {
	constructor(props) {
        super(props);
        this.state = {
            comments:props.items
        }

        // console.log(Object.keys(this.state.comments).length);
        
    }

    static getDerivedStateFromProps(nextProps, prevState){
        return null;
    }



    render(){

        let outsideDivClass = "p-3";

        if(Object.keys(this.state.comments).length > 0){
            outsideDivClass += " border-top";
        }

        return(
        	<div className={outsideDivClass}>
                {this.state.comments.map(item => (
                    <div key={"comment_"+item.comment_id} className="my-3">
                        <div className="row" >
                            <div className="col-auto">
                                <img src="/img/avatar.jpg" alt="..." className="rounded-circle" style={{width: 40+'px', height: 40+'px'}} />
                            </div>

                            <div className="col-auto mr-auto">
                                <div>{item.user.name}</div>
                                <div className="row" >
                                    <div className="col-auto">
                                        {item.content}
                                    </div>
                                    <div className="col-auto">
                                        {item.created_at.date}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        )
    }
}