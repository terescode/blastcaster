/* globals terescode */
/* eslint-env browser, es6 */
import React from 'react';
import ReactDOM from 'react-dom';
import injectTapEventPlugin from 'react-tap-event-plugin';
import BlastForm from './BlastForm';

injectTapEventPlugin();
ReactDOM.render(
  <BlastForm data={terescode.bc_data} />,
  document.getElementById('blastcaster-root')
);
