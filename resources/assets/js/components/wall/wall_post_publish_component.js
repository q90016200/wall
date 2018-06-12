
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Wall_post_component from './wall_post_component.js';

export default class Wall_post_publish_component extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            textarea_value:'',
            url_perview_status:false,
            url_perview_get_status:true,
            url_perview_img:null,
            url_perview_title:null,
            url_perview_description:null,
            url_perview_url:null,
            photo_status:false,
            photo_src:null,
            photo_input_val:'',
            photo_file:'',
            publish_status:true
        };

        this.handlePublishTextAreaChange = this.handlePublishTextAreaChange.bind(this);
        this.handlePublishUploadIMGClick = this.handlePublishUploadIMGClick.bind(this);
        this.handlePublishUploadIMGCancel = this.handlePublishUploadIMGCancel.bind(this);
        this.handlePublishShareCancel = this.handlePublishShareCancel.bind(this);
        this.handlePublishFileChange = this.handlePublishFileChange.bind(this);
        this.publishClick = this.publishClick.bind(this);
        this.checkLogin = this.checkLogin.bind(this);
    }

    componentDidMount(){
        autosize(document.getElementById("publish_textarea"));
    }

    // 檢查登入
    checkLogin(event){
        if(this.props.username == "guest"){
            event.preventDefault();
            swal("","未登入","warning");
        }
    }

    // 紀錄發佈的文字
    handlePublishTextAreaChange(event){

        let str = event.target.value;
        let class_this = this;

        this.setState({
            textarea_value: str,
        });

        // 查看有網址就抓取預覽
        if(this.state.url_perview_get_status){
            let find_url = wall_publish_find_url(str);
            // console.log(find_url);

            if(find_url != null){

                this.setState({
                    url_perview_get_status:false
                });
                
                axios({
                    method: 'post',
                    url: '/wall/get_preview',
                    data: {
                        url: find_url[0],
                    }
                }).then(function(response) {

                    let data = response.data;

                    if(!data.error){

                        // console.log(data.preview_data);
                        class_this.setState({
                            url_perview_status:true,
                            url_perview_img:data.preview_data.link_image,
                            url_perview_title:data.preview_data.link_title,
                            url_perview_description:data.preview_data.link_description,
                            url_perview_url:data.preview_data.link_url
                        });
                    }
                });
                
            }
        }

    }

    // 取消分享網址預覽
    handlePublishShareCancel(event){
        this.setState({
            url_perview_status:false,
            url_perview_get_status:true
        });
    }

    // 當點擊上傳團片圖示,觸發上傳檔案
    handlePublishUploadIMGClick(event){
        event.preventDefault();
        document.getElementById('publish_upload_img_input').click();
    }

    // 當圖片上傳 input 有圖就顯示預覽
    handlePublishFileChange(event){

        // console.log(event.target.value);
        // console.log(event.target.files[0]);

        let class_this = this;
        let files = event.target.files;

        //檢查檔案格式
        let uptypes = [
            "image/jpg",
            "image/jpeg",
            "image/pjpeg",
            "image/png"
        ];

        let fileType = files[0].type;

        if (uptypes.indexOf(fileType) == -1) {
            this.setState({
                photo_file:""
            });
            return swal("","很抱歉，圖片類型錯誤", "warning");
        }

        // 檢查檔案大小
        if (files[0].size > 10 * 1024 * 1024) {
            this.setState({
                photo_file:""
            });
            return swal("","很抱歉，上傳的圖片大小不可超過 10 MB", "warning");
        }

        
        this.setState({
            photo_file:files[0]
        })

        // 使用HTML5 File API, 來即時預覽image

        let reader = new FileReader();

        reader.onload = function (e) {
            // console.log(e);
            // console.log(e.target.result);
            class_this.setState({
                photo_src:e.target.result,
                photo_status:true,
                url_perview_status:false,
                url_perview_get_status:false
            });
        }

        reader.readAsDataURL(files[0]);
        
    }

    // 取消上傳圖片 (關閉圖片預覽)
    handlePublishUploadIMGCancel(event){

        this.setState({
            photo_src:null,
            photo_status:false,
            photo_input_val:'',
            url_perview_get_status:true
        });

    }

    // 發布
    publishClick(event){

        let class_this = this;

        let textarea_value = this.state.textarea_value.trim();
        // console.log(textarea_value.length);


        if(this.state.publish_status &&(textarea_value.length > 0 || this.state.url_perview_status || this.state.photo_status)){

            class_this.setState({
                publish_status:false
            });

            let request_data = new FormData();

            request_data.append('post_content',textarea_value);

            if(this.state.url_perview_status){
                request_data.append('post_preview_link',this.state.url_perview_url);
            }

            if(this.state.photo_status){
                request_data.append('post_img',this.state.photo_file);
                // console.log(this.state.photo_file);
            }

            // console.log(request_data);

            axios({
                method: 'post',
                url: '/wall/posts',
                data: request_data
            }).then(function(response) {

                let data = response.data;

                if(data.error == false){
                    // 恢復預設
                    class_this.setState({
                        textarea_value:'',
                        url_perview_status:false,
                        url_perview_get_status:true,
                        photo_status:false,
                        photo_file:'',
                        publish_status:true
                    });
                }

                ReactDOM.render(
                    <Wall_post_component items={data.posts_data} append="before" />,
                    document.getElementById('wall_posts')
                );
                
            });


        }else{
            swal('','請輸入分享內容','warning');
        }
    }


    render(){
        let url_perview_div = null;
        let photo_div = null;
        if (this.state.url_perview_status){
            url_perview_div = <Wall_post_publish_share img={this.state.url_perview_img} title={this.state.url_perview_title}  description={this.state.url_perview_description} url={this.state.url_perview_url} onCloseClick={this.handlePublishShareCancel}  />;  
        }

        if (this.state.photo_status){
            photo_div = <Wall_post_publish_img img={this.state.photo_src} onCloseClick={this.handlePublishUploadIMGCancel} />  
        }

        return(
            <div className="my-3 p-3 bg-white rounded border-bottom" onClick={this.checkLogin}>
                <div className="row bd-highlight mb-3">

                    <div className="col-auto mr-auto">
                        <span className="oi oi-person"></span>
                        <span className="ml-1"> {this.props.username} </span>    
                    </div>

                    <div className="col-auto">
                        <input type="file" id="publish_upload_img_input" className="d-none" accept="image/*" onChange={this.handlePublishFileChange} value={this.state.photo_input_val} />
                        <a href="" onClick={this.handlePublishUploadIMGClick} >
                            <span className="oi oi-image" ></span>
                        </a>
                    </div>
                </div>

                <form>
                    <div className="form-group">
                        <textarea className="form-control" id="publish_textarea" value={this.state.textarea_value} onChange={this.handlePublishTextAreaChange} rows="3" placeholder="分享新消息" />
                    </div>
                </form>

                {url_perview_div}
                
                {photo_div}

                <div className="text-right mt-3">
                    <button type="button" className="btn btn-info" onClick={this.publishClick}>送出</button>
                </div>

            </div>
            
        );
    }
    

}


// 預覽 分享網址
function Wall_post_publish_share(props){

    function handleClick(e){
        props.onCloseClick(e)
    }

    let shareImg = null;

    if(props.img ){
        shareImg = <img className="card-img-top" src={props.img}/>
    }

    return (
        <div className="card mt-3 ">
            <button type="button" className="close position-absolute " aria-label="Close" onClick={handleClick}>
                <span aria-hidden="true">&times;</span>
            </button>
            
            {shareImg}

            <div className="card-body">
                <h5 className="card-title">{props.title}</h5>
                <p className="card-text">{props.description}</p>
                <p className="card-text"><small className="text-muted">{props.url}</small></p>
            </div>

        </div>
    );
}

// 預覽上傳圖片
function Wall_post_publish_img(props){

    function handleClick(e){
        props.onCloseClick(e)
    }

    return (
        <div className="card mt-3 " >
            <button type="button" className="close position-absolute" aria-label="Close" onClick={handleClick}>
                <span aria-hidden="true">&times;</span>
            </button>
            <img className="card-img-top" src={props.img} alt="" />
        </div>
    );
}

// 查看文字內是否有網址
function wall_publish_find_url(str){
    // 換行轉成空白 防誤判
    str = str.replace(/\r\n|\n/g," ");

    // 找網址
    var urlPattern = /([a-z]+\:\/+)([^\/\s]*)([a-z0-9\-@\^=%&;\/~\+]*)[\?]?([^ \#]*)#?([^ \#]*)/ig; 

    var ref = str.match(urlPattern);

    return ref;

}


