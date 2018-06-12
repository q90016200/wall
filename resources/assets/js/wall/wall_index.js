
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
// <Wall_post_publish_component username={user.name} />

class Index extends React.Component {
    constructor(props) {
        super(props);
    }

    render(){
        return(
            
            <Router>
                <div>
                    <Route exact path="/" render={() => (
                        <div>
                            <Wall_post_publish_component username={user.name} />
                            <Wall_post_component items={[]} />
                        </div>
                    )} /> 

                    <Route path="/posts/:id" render={({match}) => ( 
                        <div>
                            <h3>ID: {match.params.id}</h3>
                        </div>
                    )} /> 
                </div>

            </Router>
       
        )
    }
}

// 載入 
ReactDOM.render(
    <Index />,
    document.getElementById('content')
);

