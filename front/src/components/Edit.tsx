import Modal from 'react-modal';
import PostForm from './PostForm';
import React, {ReactNode} from 'react';
import {BlogPostData} from "../containers/posts";

interface EditProps {
  handleSubmit: Function,
  postId: string,
}

interface EditState {
  showModal: boolean,
}

export default class Edit extends React.Component<EditProps, EditState> {
  constructor(props: EditProps) {
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
    const postId: string = this.props.postId;
    const showModal: boolean = this.state.showModal;

    return (
      <div>
        <button className="btn-action btn-create-post" onClick={this.handleOpenModal}>Edit</button>
        <Modal isOpen={showModal} contentLabel={"Edit post " + postId}>
          <PostForm postId={postId} handleSubmit={this.handleSubmit}/>
          <button className="btn-action btn-create-post" onClick={this.handleCloseModal}>Cancel</button>
        </Modal>
      </div>
    );
  }
}
