
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import {
    BrowserRouter as Router,
    Route,
    Link
} from 'react-router-dom';

import Wall_post_publish_component from '../components/wall/wall_post_publish_component.js';
import Wall_post_component from '../components/wall/wall_post_component.js';



// 設定 moment 語系
moment.locale("zh-tw");

class Index extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
        	items:[],
        	append:null,
        }

        this.uploadItems = this.uploadItems.bind(this);
        
    }

    render(){
        return(
            
            <Router>
                <div>
                    <Route exact path="/" render={() => (
                        <div>
                            <Wall_post_publish_component username={user.name} onUpdate={this.uploadItems} />
                            <Wall_post_component items={this.state.items} append={this.state.append} onUpdate={this.uploadItems} />
                        </div>
                    )} /> 

                    <Route path="/posts/:id" render={({match}) => ( 
                    	<Wall_post_component items={[]} post_id={match.params.id} />
                    )} /> 
                </div>

            </Router>
       
        )
    }

    uploadItems(items,type){

    	let ts = this;
    	if(type == "add_before"){
    		
	    	this.setState({
	    		items: items.concat(ts.state.items),
	    		append:"add_after"
	    	});
    	}else if(type == "add_after"){
    		this.setState({
	    		items: ts.state.items.concat(items),
	    		append:"add_before"
	    	});
    	}else if(type == "remove"){
    		this.setState({
	    		items: items,
	    		append:"remove"
	    	});
    	}
    }


}

// 載入 
ReactDOM.render(
    <Index />,
    document.getElementById('content')
);

