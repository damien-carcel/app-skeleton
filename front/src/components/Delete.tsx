import React, {ReactNode} from 'react';

interface DeleteProps {
  handleDelete: (userId: string) => void;
  userId: string;
}

export default class Delete extends React.Component<DeleteProps, {}> {
  constructor(props: DeleteProps) {
    super(props);
  }

  public handleDelete(userId: string): void {
    this.props.handleDelete(userId);
  }

  public render(): ReactNode {
    return (
      <div>
        <button
          className='btn-action btn-create-user'
          onClick={this.handleDelete.bind(this, this.props.userId)}
        >
          Delete
        </button>
      </div>
    );
  }
}
