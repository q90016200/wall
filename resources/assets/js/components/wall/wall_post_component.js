import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Wall_post_comment_component from './wall_post_comment_component.js';
import Wall_post_comment_publish_component from './wall_post_comment_publish_component.js';

export default class Wall_post_component extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            items:props.items,
        }

        // console.log(this.state.items);

        // let item = this.state.items;

        // console.log(item[0].comment_data.comments[0]);
        // item[0].comment_data.comments[0].content = "我是修改後的文字";

        this.commentPublish = this.commentPublish.bind(this);
        this.commentRemove = this.commentRemove.bind(this);

    }

    static getDerivedStateFromProps(nextProps, prevState){
        if(nextProps.items !== prevState.items){
            if(nextProps.append == "after"){
                return {
                    items: prevState.items.concat(nextProps.items),
                };
            }else if(nextProps.append == "before"){
                return {
                    items: nextProps.items.concat(prevState.items),
                };
            }
        }

        return null;
    }

    // componentWillReceiveProps(nextProps){

    //     if(nextProps.append == "after"){
    //         this.setState(prevState => ({
    //             items: prevState.items.concat(nextProps.items),
    //         }));
    //     }else if(nextProps.append == "before"){
    //         this.setState(prevState => ({
    //             items: nextProps.items.concat(prevState.items),
    //         }));
    //     }

    // }

    componentDidMount(){

    }

    render(){
        return(
            <div className="">
                {this.state.items.map((item,index) => (
                    <div className=" my-3 p-3 bg-white rounded border-bottom" key={"post_"+item.post_id}>
                        <Wall_post_head name={item.user.name} time={item.create_date} post_id={item.post_id} />

                        <Wall_post_content content={item.content} />

                        {typeof item.preview != "undefined" &&
                            <Wall_post_link_preview item={item.preview} />
                        }
                        
                        <Wall_post_comment_component items={item.comment_data.comments} onRemove={this.commentRemove}  />
                        

                        <Wall_post_comment_publish_component post_id={item.post_id} itemNum={index} onPublish={this.commentPublish}/>

                    </div>
                ))}
            </div>
        )
    } 
    // 發布留言
    commentPublish(event,itemNum,comments){
        let prevItem = this.state.items;

        let prevComments = prevItem[itemNum].comment_data.comments;
        let newComments = prevComments.concat(comments);

        let newItem = this.state.items;

        // console.log(newComments);

        newItem[0].comment_data.comments = newComments;
        
        this.setState({
            items:newItem,
            append:null
        });

    }
    // 刪除留言
    commentRemove(event,commentId){
        console.log(`removeComment:${commentId}`);
    }

}

class Wall_post_head extends React.Component {
    constructor(props){
        super(props);
        this.state = {
            isCopy:true
        }

        this.copyLink = this.copyLink.bind(this);
        this.copyLinkSpan = React.createRef();
    }

    render(){

        let copyLinkSpan = null;
        if(this.state.isCopy){
            copyLinkSpan = <span ref={this.copyLinkSpan}></span>;
        }

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
                    {copyLinkSpan}
                    <div className="dropdown-menu" aria-labelledby="post_action_men">
                        <a className="dropdown-item" onClick={this.copyLink}>複製連結</a>
                        <a className="dropdown-item" >刪除</a>
                    </div>
                </div>
            </div>
        )
    }
    // 複製連結功能
    copyLink(e,post_id){
        console.log(`copyLink:${this.props.post_id}`);

        this.setState({
            isCopy:true
        });

        this.copyLinkSpan.current.textContent ="test copy 測試複製!";

        // console.log(this.copyLinkSpan.current.textContent);

        // We will need a range object and a selection.
        var range = document.createRange(),
            selection = window.getSelection();

        // Clear selection from any previous data.
        selection.removeAllRanges();

        // Make the range select the entire content of the contentHolder paragraph.
        range.selectNodeContents(this.copyLinkSpan.current);

        // Add that range to the selection.
        selection.addRange(range);

        // Copy the selection to clipboard.
        document.execCommand('copy');

        // This is just personal preference.
        // I prefer to not show the the whole text area selected.
        e.target.focus();

        this.setState({
            isCopy:false
        });

    }

}

class Wall_post_content extends React.Component {
    constructor(props){
        super(props);
        this.state = {
            content:this.props.content
        }
    }

    componentDidMount(){
        let content = this.props.content;
        // 轉超連結
        
        // console.log(content);

        content = wrapPostContentURLs(content,true);

        //替換所有的換行符
        content = content.replace(/\r\n/g, "<br>");
        content = content.replace(/\n/g, "<br>");

        this.setState({
            content: content
        });
    }

    render(){
        return(
            <div className="my-3" dangerouslySetInnerHTML={export_html(this.state.content)}></div>
        )
    }

}

class Wall_post_link_preview extends React.Component {
    constructor(props){
        super(props);
        this.state = {
            
        }
    }

    render(){
        const isYoutube = this.props.item.youtube;
        let linkImg = null;
        if(this.props.item.link_image ){
            linkImg = <img className="card-img-top" src={this.props.item.link_image}/>
        }

        return(
            <div>
                {isYoutube ? (
                    <div className="embed-responsive embed-responsive-16by9">
                        <iframe className="embed-responsive-item" src={"https://www.youtube.com/embed/"+this.props.item.youtube}></iframe>
                    </div>
                ) : (

                    <div className="card mt-3 ">
                        {linkImg}
                        <div className="card-body">
                            <h5 className="card-title">{this.props.item.link_title}</h5>
                            <p className="card-text">{this.props.item.link_description}</p>
                            <p className="card-text"><small className="text-muted">{this.props.item.link_url}</small></p>
                        </div>
                    </div>


                    
                )}
            </div>
        )
    }
}




// 輸出html
function export_html(content) {
  return {__html: content};
}

// find url in string
var wrapPostContentURLs = function (text ,new_window) {
    let url_pattern = /((https?|telnet|ftp):[\/\/[\.\-\_\/a-zA-Z0-9\~\?\%\#\=\@\:\&\;\*\\\-]+?)(?=[.:?\\-]*(?:[^\/\/[\.\-\_\/a-zA-Z0-9\~\?\%\#\=\@\:\&\;\*\\\-]|$))/;
    let target = (new_window === true || new_window == null) ? '_blank' : '';

    return text.replace(url_pattern, function (url) {
        // let protocol_pattern = /^(?:(?:https?|ftp):\/\/)/i;
        // let href = protocol_pattern.test(url) ? url : 'http://' + url;
        let href = url;
        return '<a href="' + href + '" target="' + target + '" >' + url + '</a>';
    });
};

