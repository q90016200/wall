
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
        this.handlePublishUploadIMGClick = this.handlePublishUploadIMGClick.bind(this);
        this.handlePublishFileChange = this.handlePublishFileChange.bind(this);
    }

    // 紀錄發佈的文字
    handlePublishTextAreaChange(event){

        let str = event.target.value;

        this.setState({
            textarea_value: str,
        });


        let find_url = wall_publish_find_url(str);

        console.log(find_url);


        if(find_url != null){


            axios({
                method: 'post',
                url: '/wall/get_preview',
                data: {
                    url: find_url[0],
                }
            }).then(function(response) {
                console.log(response);
            });

            // this.setState({
            //     share_status:true
            // });
        }

    }

    // 當點擊上傳團片圖示,觸發上傳上傳檔案
    handlePublishUploadIMGClick(event){
        event.preventDefault();
        document.getElementById('publish_upload_img_input').click()
    }

    // 當圖片上傳 input 有圖就顯示預覽
    handlePublishFileChange(event){
        this.setState({
            photo_status:true
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
                        <a href="" onClick={this.handlePublishUploadIMGClick} >
                            <span className="oi oi-image" onClick={this.handlePublishUploadIMGClickhandlePublishUploadIMGClick}></span>
                        </a>
                        <input type="file" id="publish_upload_img_input" className="invisible" onChange={this.handlePublishFileChange} />
                    </div>
                </div>
                <form>
                    <div className="form-group">
                        <textarea className="form-control" value={this.state.textarea_value} onChange={this.handlePublishTextAreaChange} rows="3" placeholder="分享新消息" />
                    </div>
                </form>
                {share_div}

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
            <img className="card-img-top" src="" alt="Card image cap" />
            <div className="card-body">
                <h5 className="card-title">Card title</h5>
                <p className="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                <p className="card-text"><small className="text-muted">Last updated 3 mins ago</small></p>
            </div>
        </div>
    );
}

// 預覽上傳圖片
function Wall_post_publish_img(props){
    return (
        <div className="card mt-3" >
            <img className="card-img-top" src="" alt="" />
        </div>
    );
}

// 查看文字內是否有網址
function wall_publish_find_url(str){
    var urlPattern = /([a-z]+\:\/+)([^\/\s]*)([a-z0-9\-@\^=%&;\/~\+]*)[\?]?([^ \#]*)#?([^ \#]*)/ig; 

    var ref = str.match(urlPattern);

    return ref;
}


