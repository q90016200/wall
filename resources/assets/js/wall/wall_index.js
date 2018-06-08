
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import {
    BrowserRouter as Router,
    Route,
    Link
} from 'react-router-dom'

import Wall_post_publish_component from '../components/wall/wall_post_publish_component.js';
import Wall_post_component from '../components/wall/wall_post_component.js';

// 載入 發布 dom
ReactDOM.render(
    <Wall_post_publish_component username={user.name} />,
    document.getElementById('wall_publish')
);
// 載入 post dom
ReactDOM.render(
    <Wall_post_component />,
    document.getElementById('wall_posts')
);

class BasicExample extends React.Component {

    render(){
        return (
            <Router>
                <div>
                <ul>
                    <li><Link to="/">首頁</Link></li>
                    <li>
                        <Link to="/about">About</Link>
                    </li>
                </ul>

                <hr/>

                <Route exact path="/" component={Home}/>
                <Route exact path="/about" component={About}/>

                </div>
            </Router>
        )
    }
    
}

const Home = () => (
    <div>
        <h2>首頁</h2>
    </div>
)

const About = () => (
    <div>
        <h2>About</h2>
    </div>
)
// 載入 spa demo
ReactDOM.render(
    <BasicExample />,
    document.getElementById('spa')
);



