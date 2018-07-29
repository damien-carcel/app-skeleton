import Create from './Create';
import Post from './Post';
import React, {ReactNode} from 'react';
import {BlogPostData, createPost, deletePost, listPosts, updatePost} from '../containers/posts';
import {isEmpty} from "../tools/isEmpty";

interface ListPostsProps {
}

interface ListPostsState {
  error: {[key: string]: any},
  isLoaded: boolean,
  posts: Array<BlogPostData>,
}

export default class ListPosts extends React.Component<ListPostsProps, ListPostsState> {
  constructor(props: ListPostsProps) {
    super(props);

    this.state = {
      error: {},
      isLoaded: false,
      posts: []
    };

    this.delete = this.delete.bind(this);
    this.submit = this.submit.bind(this);
  }

  componentDidMount(): void {
    this.getAllPosts();
  }

  delete(postId: string): void {
    this.setState({
      isLoaded: false
    });

    deletePost(postId).then(
      () => {
        this.setState(prevState => ({
          isLoaded: true,
          posts: prevState.posts.filter((post: BlogPostData) => post.id !== postId)
        }));
      },
      (error) => {
        this.setState({
          isLoaded: true,
          error: error
        });
      }
    );
  }

  submit(postId: string, data: BlogPostData): void {
    this.setState({
      isLoaded: false
    });

    if (postId) {
      updatePost(postId, data).then(() => {
        this.setState(prevState => ({
          isLoaded: true,
          posts: prevState.posts.map((post: BlogPostData) => {
            if (post.id === postId) {
              post.title = data.title;
              post.content = data.content;
            }

            return post;
          })
        }));
      }, (error) => {
        this.setState({
          isLoaded: true,
          error: error
        });
      });
    } else {
      createPost(data).then(() => this.getAllPosts());
    }
  }

  getAllPosts(): void {
    listPosts().then(
      (result) => {
        this.setState({
          isLoaded: true,
          posts: result
        });
      },
      (error) => {
        this.setState({
          isLoaded: true,
          error: error
        });
      }
    );
  }

  render(): ReactNode {
    const {error, isLoaded, posts} = this.state;

    if (!isEmpty(error)) {
      return <div>Error: {error.message}</div>;
    }

    if (!isLoaded) {
      return <div>Loading...</div>;
    }

    const renderedPosts: Array<any> = posts.map((post: BlogPostData) => {
      return <Post key={post.id}
                   post={post}
                   handleSubmit={this.submit}
                   handleDelete={this.delete}/>;
    });

    return (
      <div className="container">
        <Create handleSubmit={this.submit}/>
        <div className="blog-posts">
          {renderedPosts}
        </div>
      </div>
    );
  }
}
