import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Wall_post_comment_component extends React.Component {
	constructor(props) {
        super(props);
        this.state = {
            // comments:props.items,
            comments:[],
            commentRemoveStatus:true
        }

        // console.log(Object.keys(this.state.comments).length);

        this.commentRemove = this.commentRemove.bind(this);
        this.commentPublish = this.commentPublish.bind(this);
        
    }

    componentDidMount(){
        // dom 加載後取得 post comment
        if(this.props.comment_count > 0){
            this.getComment(this.props.post_id);
        }
        
    }

    componentWillUnmount() {

    }


    render(){

        let outsideDivClass = "p-3";

        if(Object.keys(this.state.comments).length > 0){
            outsideDivClass += " border-top";
        }

        return(
        	<div className={outsideDivClass}>
                {/* 留言區塊 */}
                {this.state.comments.map((item,index) => (
                    <div key={"comment_"+item.comment_id} className="my-3">
                        <div className="row" >
                            <div className="col-auto">
                                <img src="/img/avatar.jpg" className="rounded-circle" style={{width: 40+'px', height: 40+'px'}} />
                            </div>

                            <div className="col-auto mr-auto">
                                <div>{item.user.name}</div>
                                <div className="row" >
                                    <div className="col-auto">
                                        {item.content}
                                    </div>
                                    <div className="col-auto">
                                        {moment(item.created_at.date).format('YYYY-MM-DD HH:m')}
                                        {item.is_edit &&
                                            <button type="button" className="close position-absolute" aria-label="Close" onClick={(event)=>this.commentRemove(event,index,item.comment_id)}>
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ))}
                {/* 留言區塊 END */}

                {/* 發布留言區塊 */}
                <Wall_post_comment_publish_component post_id={this.props.post_id} onPublish={this.commentPublish} />
            
            </div>
        )
    }

    // 取得 comment
    getComment(postId){
        let ts = this;

        axios.get(`/wall/posts/${postId}/comments`).then(function(response){
            let data = response.data;
            if(data.total > 0){
                ts.setState({
                    comments:data.comments
                });
            }
        });
    }

    // 刪除留言
    commentRemove(event,index,comment_id){
        let ts = this;

        // console.log(event.target);

        if(this.state.commentRemoveStatus){
            ts.setState({
                commentRemoveStatus:false
            });

            swal({
                title: "確定刪除此留言?",
                text: "",
                icon: "warning",
                buttons:["取消","刪除"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.delete(`/wall/posts/${ts.props.post_id}/comments/`+comment_id).then(function(response){
                        let data = response.data;
                        // console.log(data);

                        if(data.error == false){
                             let prevComments = ts.state.comments;
                            let newComments = new Array();
                            for (var i = 0; i < prevComments.length; i++) {
                                if(prevComments[i].comment_id != comment_id){
                                    newComments.push(prevComments[i]);
                                }
                            }

                            swal("刪除留言成功", {
                                icon: "success",
                            }).then(()=>{
                                ts.setState({
                                    commentRemoveStatus:true,
                                    comments:newComments
                                });
                            });   
                        }else{
                            ts.setState({
                                commentRemoveStatus:true,
                            });
                        }

                    })

                    
                }
            });

        }

    }

    // 發布留言
    commentPublish(event,comment){
        let prevComments = this.state.comments;
        let newComments = prevComments.concat(comment);

        this.setState({
            comments:newComments
        });
    }

}


class Wall_post_comment_publish_component extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
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
    commentPublish(event){

        let ts = this;

        if(this.state.publishStatus && this.state.commentInputVal.length > 0){

            ts.setState({
                publishStatus:false
            });

            axios.post(`/wall/posts/${ts.props.post_id}/comments`,{
                post_id:this.props.post_id,
                content:this.state.commentInputVal
            }).then(function(response){

                ts.setState({
                    publishStatus:true,
                    commentInputVal:''
                });
                
                let data = response.data;

                if(data.error == false){
                    ts.props.onPublish(event,data.comments);
                }

            });

        }


    }
}