import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import {
    BrowserRouter as Router,
    Route,
    Link
} from 'react-router-dom';
import Wall_post_comment_component from './wall_post_comment_component.js';

export default class Wall_post_component extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            items:props.items,
            // items: [],
            loadPost: (this.props.load !== undefined) ? this.props.load : true,
            loadPage: (this.props.page !== undefined) ? this.props.page : 1,
            loadTime: (this.props.loadTime !== undefined) ? this.props.loadTime : new Date().valueOf(),

        }

        // console.log(this.state);

        // let item = this.state.items;

        // console.log(item[0].comment_data.comments[0]);
        // item[0].comment_data.comments[0].content = "我是修改後的文字";


        this.handleScroll = this.handleScroll.bind(this);
        this.postRemove = this.postRemove.bind(this);
        this.commentCountUpdate = this.commentCountUpdate.bind(this);

    }

    static getDerivedStateFromProps(nextProps, prevState){

        // console.log(nextProps);

        if(nextProps.items !== prevState.items){
            if(nextProps.append == "add"){
                return {
                    items: nextProps.items
                };
            }else if(nextProps.append == "remove"){
                return {
                    items: nextProps.items
                };
            }
        }

        return null;
    }

    componentDidMount(){
        // dom 加載後取得 post
        if(this.state.items.length == 0){
            this.getPost();
        }

        window.addEventListener('scroll', this.handleScroll);
    }

    componentWillUnmount() {
       // 取消監聽scroll load post
       window.removeEventListener('scroll', this.handleScroll);
    }

    render(){
        return(
            <div className="my-3 ">
                {this.state.items.map((item,index) => (
                    <div className=" my-3 p-3 bg-white rounded border-bottom" key={"post_"+item.post_id}>
                        <Wall_post_head name={item.user.name} time={item.create_date.date} post_id={item.post_id} onRemove={this.postRemove} />

                        <Wall_post_content content={item.content} />

                        {typeof item.preview != "undefined" &&
                            <Wall_post_link_preview item={item.preview} />
                        }
                        
                        <Wall_post_comment_component post_id={item.post_id} comment_count={item.comment_count} onUpdateCommentCount={this.commentCountUpdate} index={index} checkLogin={this.props.checkLogin} />

                    </div>
                ))}
            </div>
        )
    } 

    // 取得 post
    getPost(){

        let ts = this;

        if(this.state.loadPost){

            ts.setState({
                loadPost:false
            });

            if(typeof ts.props.onUploadPage != "undefined"){
                ts.props.onUploadPage(ts.state.loadPage,false);
            }

            let get_params = {
                t: this.state.loadTime,
                page:this.state.loadPage
            }

            if(this.props.post_id){
               get_params.post_id =  this.props.post_id;
            }


            // 載入最新5篇
            axios.get("/wall/posts",{
                params: get_params
            }).then(function(response){
                // console.log(response.data);
                let data = response.data;

                if(typeof ts.props.onUpdate == "undefined"){
                    ts.setState({
                        items:ts.state.items.concat(data.posts)
                    });
                }else{
                    ts.props.onUpdate(data.posts,"add_after");
                }
                
                if( ts.state.loadPage < data.total_page){
                    ts.setState({
                        loadPost:true,
                    });

                    if(typeof ts.props.onUploadPage != "undefined"){
                        ts.props.onUploadPage(ts.state.loadPage,true);
                    }

                }

            });
        }
        
    }

    // 監聽scroll 來觸發取得 post
    handleScroll(e){

        let ts = this;

        // console.log("scrollLoadPost");

        if (ts.state.loadPost) {

            let wh = window.innerHeight;
            let wstop = document.documentElement && document.documentElement.scrollTop || document.body.scrollTop;

            let el_wall_content = document.getElementById("content");

            // console.log(`w1:${(wh + wstop)}`);
            // console.log(`w2:${(el_wall_content.clientHeight * 0.85)}`);

            if (wh + wstop > el_wall_content.clientHeight * 0.85) {
                ts.setState({
                    loadPage:(ts.state.loadPage + 1)
                });
                ts.getPost();
            }
        }
    }

    // 刪除貼文
    postRemove(event,post_id){
        var ts = this;

        var cl = this.props.checkLogin(event);

        if(cl){
            swal({
                title: "確定刪除此貼文?",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then((willDelete)=>{
                if(willDelete){
                    axios.delete(`/wall/posts/${post_id}`).then((response)=>{
                        if(response.error == false){
                            // 要回到父層才能變更items
                            let prevPosts = ts.state.items;
                            let newPosts = new Array();

                            for (var i = 0; i < prevPosts.length; i++) {
                                if(prevPosts[i].post_id != post_id){
                                    newPosts.push(prevPosts[i]);
                                }
                            }


                            if(typeof ts.props.onUpdate == "undefined"){
                                ts.setState({
                                    items:newPosts
                                });
                            }else{
                                ts.props.onUpdate(newPosts,"remove");
                            }

                            swal("","刪除貼文成功！","success");
                        }
                        
                    });
                }
            });
        }

        

    }

    // 更新留言數
    commentCountUpdate(index,count){
        console.log("comment:"+index);
        console.log("commentCount:"+count);

        var posts = this.state.items;
        posts[index].comment_count = count ;

        this.props.onUpdate(posts);

    }


}

// Prop 預設值，若對應 props 沒傳入值將會使用 default 值 null
Wall_post_component.defaultProps = {
    append: null,
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
        let ts = this;
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
                    <div>
                        <Link to={`/wall/posts/${this.props.post_id}`}>
                            {moment(this.props.time).fromNow()}
                        </Link>
                    </div>
                </div>
                
                <div className="col-auto">
                    <button className="btn bg-white" type="button" id="post_action_men" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ...
                    </button>
                    {copyLinkSpan}
                    <div className="dropdown-menu" aria-labelledby="post_action_men">
                        <a className="dropdown-item" onClick={(event)=>this.copyLink(event,this.props.post_id)}>複製連結</a>
                        <a className="dropdown-item" onClick={function(event){ts.props.onRemove(event,ts.props.post_id)}}>刪除</a>
                    </div>
                </div>
            </div>
        )
    }

    // 複製連結功能
    copyLink(e,post_id){
        // console.log(`post_id:${post_id}`);
        // console.log(e.target);

        var target = e.target;

        var ts = this;

        this.setState({
            isCopy:true
        });

        setTimeout(function(){
            return ts.copyLink2(target,post_id);
        },100);

    }

    copyLink2(target,post_id){

        var copyText = window.location.protocol + "//" + window.location.host + "/wall/posts/" + post_id;

        // console.log("copy:");
        // console.log(copyText);

        this.copyLinkSpan.current.textContent = copyText;

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
        target.focus();

        swal("","複製網址成功","success");

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
    // let url_pattern = /((https?|telnet|ftp):[\/\/[\.\-\_\/a-zA-Z0-9\~\?\%\#\=\@\:\&\;\*\\\-]+?)(?=[.:?\\-]*(?:[^\/\/[\.\-\_\/a-zA-Z0-9\~\?\%\#\=\@\:\&\;\*\\\-]|$))/;
    let url_pattern = /(https?\:\/\/+)([^\/\s]*)([a-z0-9\.\/\?\=\_\-\&\~\%\#]*)/igm;
    let target = (new_window === true || new_window == null) ? '_blank' : '';

    return text.replace(url_pattern, function (url) {
        // let protocol_pattern = /^(?:(?:https?|ftp):\/\/)/i;
        // let href = protocol_pattern.test(url) ? url : 'http://' + url;
        let href = url;
        return '<a href="' + href + '" target="' + target + '" >' + url + '</a>';
    });
};

