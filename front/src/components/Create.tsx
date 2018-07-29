import Modal from 'react-modal';
import PostForm from './PostForm';
import React, {ReactNode} from 'react';
import {BlogPostData} from "../containers/posts";

interface CreateProps {
  handleSubmit: Function,
}

interface CreateState {
  showModal: boolean,
}

export default class Create extends React.Component<CreateProps, CreateState> {
  constructor(props: CreateProps) {
    super(props);

    this.state = {
      showModal: false
    };

    this.handleCloseModal = this.handleCloseModal.bind(this);
    this.handleOpenModal = this.handleOpenModal.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  handleCloseModal(): void {
    this.setState({showModal: false});
  }

  handleOpenModal(): void {
    this.setState({showModal: true});
  }

  handleSubmit(postId: string, data: BlogPostData): void {
    this.props.handleSubmit(postId, data);

    this.setState({showModal: false});
  }

  render(): ReactNode {
    const showModal: boolean = this.state.showModal;

    return (
      <div>
        <button className="btn-action btn-create-post" onClick={this.handleOpenModal}>Create a new post</button>
        <Modal isOpen={showModal} contentLabel="Create a new post">
          <PostForm postId={''} handleSubmit={this.handleSubmit}/>
          <button className="btn-action btn-create-post" onClick={this.handleCloseModal}>Cancel</button>
        </Modal>
      </div>
    );
  }
}
