/**
 * Import
 */
import React from 'react';
import { shallow } from 'enzyme';
import { should } from 'chai';

// Components
import App from 'src/components/App';

// préparation de should
should();

describe('Série de tests : App', () => {
  const comp = shallow(<App />);
  it('should have prop className', () => {
    comp.props().should.have.property('className');
  });

  // un test suspendu avec skip
  it.skip('should not have prop id', () => {
    comp.props().should.not.have.property('id');
  });
});
