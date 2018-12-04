import React, {ReactNode} from 'react';
import Modal from 'react-modal';
import {UserData} from '../containers/user';
import UserForm from './UserForm';

interface EditProps {
  handleSubmit: (userId: string, data: UserData) => void;
  userId: string;
}

interface EditState {
  showModal: boolean;
}

export default class Edit extends React.Component<EditProps, EditState> {
  constructor(props: EditProps) {
    super(props);

    this.state = {
      showModal: false,
    };

    this.handleCloseModal = this.handleCloseModal.bind(this);
    this.handleOpenModal = this.handleOpenModal.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  public handleCloseModal(): void {
    this.setState({showModal: false});
  }

  public handleOpenModal(): void {
    this.setState({showModal: true});
  }

  public handleSubmit(userId: string, data: UserData): void {
    this.props.handleSubmit(userId, data);

    this.setState({showModal: false});
  }

  public render(): ReactNode {
    const userId: string = this.props.userId;
    const showModal: boolean = this.state.showModal;

    return (
      <div>
        <button className='btn-action btn-create-user' onClick={this.handleOpenModal}>Edit</button>
        <Modal isOpen={showModal} contentLabel={'Edit user ' + userId}>
          <UserForm userId={userId} handleSubmit={this.handleSubmit}/>
          <button className='btn-action btn-create-user' onClick={this.handleCloseModal}>Cancel</button>
        </Modal>
      </div>
    );
  }
}
