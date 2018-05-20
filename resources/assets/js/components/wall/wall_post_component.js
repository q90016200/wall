import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Wall_post_component extends React.Component {
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
            <div className="">
                {this.state.items.map(item => (
                    <div className=" my-3 p-3 bg-white rounded border-bottom" key={item.post_id}>
                        <Wall_post_head name={item.user.name} time={item.create_date} post_id={item.post_id} />

                        <Wall_post_content item={item} />
                    </div>
                ))}

            </div>
        )
    } 

}

class Wall_post_head extends React.Component {
    constructor(props){
        super(props);
        this.state = {

        }

        this.copyLink = this.copyLink.bind(this);

    }

    render(){
        return(
            <div className="row">
                <div className="col-auto">
                    <img src="/img/avatar.jpg" alt="..." className="rounded-circle" style={{width: 40+'px', height: 40+'px'}} />
                </div>

                <div className="col-auto mr-auto">
                    <div>{this.props.name}</div>
                    <div>{this.props.time}</div>
                </div>
                
                <div className="col-auto">
                    <button className="btn bg-white" type="button" id="post_action_men" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ...
                    </button>
                    <div className="dropdown-menu" aria-labelledby="post_action_men">
                        <a className="dropdown-item" onClick={this.copyLink}>複製連結</a>
                        <a className="dropdown-item" >刪除</a>
                    </div>
                </div>
            </div>
        )
    }

    copyLink(post_id){
        console.log(`copyLink:${this.props.post_id}`);
    }

}

class Wall_post_content extends React.Component {
    constructor(props){
        super(props);
        this.state = {

        }
    }

    render(){
        return(
            <div className="my-3">
                <div className="my-3">
                    {this.props.item.content}
                </div>
            </div>
        )
    }

}




