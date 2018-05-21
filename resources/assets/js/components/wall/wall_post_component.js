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

    componentWillReceiveProps(nextProps){
        // console.log(this.props);
        this.setState(prevState => ({
            items: prevState.items.concat(nextProps.items),
        }));
    }

    render(){

        return(
            <div className="">
                {this.state.items.map(item => (
                    <div className=" my-3 p-3 bg-white rounded border-bottom" key={"post_"+item.post_id}>
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
            content:this.props.item.content
        }
    }

    componentDidMount(){
        let content = this.props.item.content;
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
            <div className="my-3">
                <div className="my-3" dangerouslySetInnerHTML={export_html(this.state.content)}>
                </div>
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

