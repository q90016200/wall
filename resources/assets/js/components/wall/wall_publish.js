
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Wall_post_publish extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            textarea_value:'',
            share_status:false,
            share_div_img:null,
            share_div_title:null,
            share_div_description:null,
            share_div_url:null,
            photo_status:false,
            photo_src:null
            
        };

        this.handlePublishTextAreaChange = this.handlePublishTextAreaChange.bind(this);
        this.handlePublishUploadIMGClick = this.handlePublishUploadIMGClick.bind(this);
        this.handlePublishUploadIMGCancel = this.handlePublishUploadIMGCancel.bind(this);
        
        this.handlePublishFileChange = this.handlePublishFileChange.bind(this);
    }

    // 紀錄發佈的文字
    handlePublishTextAreaChange(event){

        let str = event.target.value;

        this.setState({
            textarea_value: str,
        });

        // 查看有網址就抓取預覽

        let find_url = wall_publish_find_url(str);

        // console.log(find_url);


        if(find_url != null){

            let class_this = this;

            axios({
                method: 'post',
                url: '/wall/get_preview',
                data: {
                    url: find_url[0],
                }
            }).then(function(response) {

                let data = response.data;

                // response = JSON.parse(response);

                if(!data.error){

                    // console.log(data.preview_data);
                    class_this.setState({
                        share_status:true,
                        share_div_img:data.preview_data.link_image,
                        share_div_title:data.preview_data.link_title,
                        share_div_description:data.preview_data.link_description,
                        share_div_url:data.preview_data.link_url
                    });
                }

                
            });

            
        }

    }

    // 當點擊上傳團片圖示,觸發上傳上傳檔案
    handlePublishUploadIMGClick(event){
        event.preventDefault();
        document.getElementById('publish_upload_img_input').click();
    }

    // 當圖片上傳 input 有圖就顯示預覽
    handlePublishFileChange(event){

        // console.log(event.target.value);
        // console.log(event.target.files[0]);

        let class_this = this;

        // 使用HTML5 File API, 來即時預覽image

        let reader = new FileReader();

        reader.onload = function (e) {
            // console.log(e);
            // console.log(e.target.result);
            class_this.setState({
                photo_src:e.target.result,
                photo_status:true
            });
        }

        reader.readAsDataURL(event.target.files[0]);
        
    }

    // 取消上傳圖片 (關閉圖片預覽)
    handlePublishUploadIMGCancel(event){
        this.setState({
            photo_src:null,
            photo_status:false
        });
    }


    render(){
        let share_div = null;
        let photo_div = null;
        if (this.state.share_status){
            share_div = <Wall_post_publish_share img={this.state.share_div_img} title={this.state.share_div_title}  description={this.state.share_div_description} url={this.state.share_div_url}  />;  
        }

        if (this.state.photo_status){
            photo_div = <Wall_post_publish_img img={this.state.photo_src} onCloseClick={this.handlePublishUploadIMGCancel} />  
        }

        return(
            <div>
                <div className="d-flex justify-content-between bd-highlight mb-3">
                    <Wall_post_publish_user name={this.props.name} />
                
                    <div></div>

                    <div className="">
                        <input type="file" id="publish_upload_img_input" className="invisible" accept="image/*" onChange={this.handlePublishFileChange} />
                        <a href="" onClick={this.handlePublishUploadIMGClick} >
                            <span className="oi oi-image" ></span>
                        </a>
                    </div>
                </div>
                <form>
                    <div className="form-group">
                        <textarea className="form-control" value={this.state.textarea_value} onChange={this.handlePublishTextAreaChange} rows="3" placeholder="分享新消息" />
                    </div>
                </form>
                {share_div}
                {photo_div}

            </div>
        );
    }
    

}

// 發布使用者顯示區塊
function Wall_post_publish_user(props) {
    return (
        <div>
            <span className="oi oi-person"></span>
            <span className="ml-1"> {props.name} </span>    
        </div>
    );
}

// 預覽 分享網址
function Wall_post_publish_share(props){
    return (
        <div className="card mt-3">
            <img className="card-img-top" src={props.img} alt="Card image cap" />
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
            <button type="button" className="close position-absolute " aria-label="Close" onClick={handleClick}>
                <span aria-hidden="true">&times;</span>
            </button>
            <img className="card-img-top" src={props.img} alt="" />
        </div>
    );
}

// 查看文字內是否有網址
function wall_publish_find_url(str){
    var urlPattern = /([a-z]+\:\/+)([^\/\s]*)([a-z0-9\-@\^=%&;\/~\+]*)[\?]?([^ \#]*)#?([^ \#]*)/ig; 

    var ref = str.match(urlPattern);

    return ref;
}


