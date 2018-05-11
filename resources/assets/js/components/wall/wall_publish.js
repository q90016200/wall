
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Wall_post_publish extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            photo_status:false,
            share_status:false,
            textarea_value:''
        };

        this.handlePublishTextAreaChange = this.handlePublishTextAreaChange.bind(this);
    }

    handlePublishTextAreaChange(event){
        this.setState({
            textarea_value: event.target.value,
            share_status:true
        });
    }

    render(){
        let share_div = null;
        if (this.state.share_status){
            share_div = <Wall_post_publish_share />;  
        }

        if (this.state.photo_status){
            photo_div = <div></div>;  
        }

        return(
            <div>
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
                <form>
                    <div className="form-group">
                        <textarea className="form-control" value={this.state.textarea_value} onChange={this.handlePublishTextAreaChange} rows="3" placeholder="分享內容" />
                    </div>
                </form>
                {share_div}

            </div>
        );
    }
    

}

function Wall_post_publish_user(props) {
    return (
        <div>
            <span className="oi oi-person"></span>
            <span className="ml-1"> {props.name} </span>    
        </div>
    );
}

function Wall_post_publish_share(props){
    return (
        <div className="card mt-3">
            <img className="card-img-top" src="" alt="Card image cap" />
            <div className="card-body">
                <h5 className="card-title">Card title</h5>
                <p className="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                <p className="card-text"><small className="text-muted">Last updated 3 mins ago</small></p>
            </div>
        </div>
    );
}