/**
* Imports.
*/
import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps  } from '@wordpress/block-editor';
import { PanelBody, PanelRow, ToggleControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { SwiperInit } from './swiper-init';
import { PanelColorSettings } from '@wordpress/block-editor';


/**
* Export.
*/
export default function Edit( {attributes, setAttributes} ) {
    const { navigation, pagination, arrowColor } = attributes;
	const style = {
        '--arrow-color': arrowColor
    };
    
    
    const swiper = SwiperInit( '.swiper', { navigation, pagination } );
    
    return (
        <>
            <InspectorControls>
                <PanelBody title={ __( 'Settings', 'testimonial-slider' ) }>
                    <PanelRow>
                        <ToggleControl
                            label={ __( 'Navigation', 'testimonial-slider' ) }
                            checked={ navigation }
                            onChange={ ( value ) =>
                                setAttributes( { navigation: value } )
                            }
                            help={ __( 'Navigation will display arrows so users can navigate forward and backward.', 'testimonial-slider' ) }
                        />
                    </PanelRow>
                    <PanelRow>
                        <ToggleControl
                            label={ __( 'Pagination', 'testimonial-slider' ) }
                            checked={ pagination }
                            onChange={ ( value ) =>
                                setAttributes( { pagination: value } )
                            }
                            help={ __( 'Pagination will display dots so users can navigate to any slide.', 'testimonial-slider' ) }
                        />
                    </PanelRow>
                </PanelBody>
				<PanelColorSettings 
				__experimentalIsRenderedInSidebar
					title={ __( 'Arrow Color', 'testimonial-slider' ) }
					colorSettings={ [
						{
							value: arrowColor,
							onChange: ( value ) => setAttributes( { arrowColor: value } ),
							label: __( 'Arrow Color', 'testimonial-slider' ),
						},
					] }					
				/>
            </InspectorControls>
            <div { ...useBlockProps()} attributes={ attributes } style={ style }>
                <ServerSideRender block="mindset-blocks/testimonial-slider" attributes={ attributes } />
            </div>
        </>
    );
}