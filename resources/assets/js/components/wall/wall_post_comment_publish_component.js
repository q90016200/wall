import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Wall_post_comment_publish_component extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            postId:this.props.post_id,
            publishStatus:true,
            commentInputVal:''
        }

        this.commentPublish = this.commentPublish.bind(this);
        this.commentInputChange = this.commentInputChange.bind(this);
        
    }

    render(){

        return(
            <div className="border-top  mt-3 pt-3 ">
                <div className="input-group mb-3 ">
                    <input type="text" className="form-control" placeholder="留言..." aria-label="留言..." aria-describedby="basic-addon2" value={this.state.commentInputVal} onChange={this.commentInputChange} maxLength="500" />
                    <div className="input-group-append">
                        <button className="btn btn-outline-secondary" type="button" onClick={this.commentPublish}>送出</button>
                    </div>
                </div>
            </div>
            
        )
    }

    commentInputChange(event){
        let str = event.target.value;
        let ts = this;

        this.setState({
            commentInputVal:str
        });

    }

    // 發布留言
    commentPublish(){

        let ts = this;

        if(this.state.publishStatus && this.state.commentInputVal.length > 0){

            ts.setState({
                publishStatus:false
            });

            axios.post("/wall/comments",{
                post_id:this.state.postId,
                content:this.state.commentInputVal
            }).then(function(response){

                ts.setState({
                    publishStatus:true,
                    commentInputVal:''
                });


                console.log(response);
                let data = response.data;
                if(data.error != false){
                    
                }


            });

        }
    }


}