import PropTypes from 'prop-types';

const Theme = props => {
	return (
		<div className="theme-view">
			<h3>Theme</h3>
			<button onClick={() => props.handleNextView(11)}>Next</button>
		</div>
	);
};

Theme.propTypes = {
	handleNextView: PropTypes.func.isRequired,
};

export default Theme;
