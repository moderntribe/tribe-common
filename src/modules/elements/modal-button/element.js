/**
 * External dependencies
 */
import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

/**
 * WordPress dependencies
 */
import { Modal } from '@wordpress/components';

/**
 * Internal dependencies
 */
import Button from '@moderntribe/common/elements/button/element';

class ModalButton extends PureComponent {
	static propTypes = {
		className: PropTypes.string,
		disabled: PropTypes.bool,
		isOpen: PropTypes.bool,
		label: PropTypes.string,
		modalClassName: PropTypes.string,
		modalContent: PropTypes.node,
		modalOverlayClassName: PropTypes.string,
		modalTitle: PropTypes.string,
		onClick: PropTypes.func,
		onClose: PropTypes.func,
		onOpen: PropTypes.func,
	};

	constructor( props ) {
		super( props );
		this.state = {
			isOpen: false,
		};
	}

	onClick = ( e ) => {
		this.props.onClick && this.props.onClick( e );
		this.onOpen();
		this.props.isOpen === undefined && this.setState( { isOpen: true } );
	};

	onRequestClose = ( e ) => {
		this.onClose( e );
		this.props.isOpen === undefined && this.setState( { isOpen: false } );
	}

	onOpen = () => this.props.onOpen && this.props.onOpen();

	onClose = ( e ) => this.props.onClose && this.props.onClose( e );

	preventClick = ( e ) => e.stopPropagation();

	preventBlur = ( e ) => e.stopPropagation();

	renderModal = () => {
		const {
			className,
			disabled,
			isOpen,
			label,
			onClick,
			onClose,
			onOpen,
			modalClassName,
			modalContent,
			modalOverlayClassName,
			modalTitle,
			...restProps
		} = this.props;

		const isModalOpen = isOpen !== undefined ? isOpen : this.state.isOpen;

		return ( isModalOpen && (
			<Modal
				className={ classNames(
					'tribe-editor__modal-button__modal-content',
					modalClassName,
				) }
				onRequestClose={ this.onRequestClose }
				overlayClassName={ classNames(
					'tribe-editor__modal-button__modal-overlay',
					modalOverlayClassName,
				) }
				title={ modalTitle }
				{ ...restProps }
			>
				{ modalContent }
			</Modal>
		) );
	};

	render() {
		const { className, disabled, label } = this.props;
		return (
			<div className={ classNames(
				'tribe-editor__modal-button',
				className,
			) }>
				<Button
					className="tribe-editor__modal-button__button"
					onClick={ this.onClick }
					disabled={ disabled }
				>
					{ label }
				</Button>
				{ this.renderModal() }
			</div>
		);
	}
}

export default ModalButton;
