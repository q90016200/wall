import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Wall_post_comment_component extends React.Component {
	constructor(props) {
        super(props);
        this.state = {
            comments:props.items,
            commentRemove:true
        }

        // console.log(Object.keys(this.state.comments).length);

        this.commentRemove = this.commentRemove.bind(this);
        
    }

    static getDerivedStateFromProps(nextProps, prevState){

        // console.log("nextProps");
        // console.log(nextProps);

        if(nextProps.items !== prevState.comments){
            return {
                comments: nextProps.items,
            }
        }

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
                                            <button type="button" className="close position-absolute" aria-label="Close" onClick={(event)=>this.commentRemove(event,this.props.itemNum,item.comment_id)}>
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        )
    }

    commentRemove(event,itemNum,comment_id){
        let ts = this;

        // console.log(event.target);

        if(this.state.commentRemove){
            ts.setState({
                commentRemove:false
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
                    axios.delete('/wall/comments/'+comment_id).then(function(response){
                        let data = response.data;
                        // console.log(data);

                        // 互叫父組件重新渲染留言
                        ts.props.onRemove(event,itemNum,comment_id);

                        swal("刪除留言成功", {
                            icon: "success",
                        }).then(()=>{
                            ts.setState({
                                commentRemove:true
                            });
                        });


                    })

                    
                }
            });

        }

    }


}