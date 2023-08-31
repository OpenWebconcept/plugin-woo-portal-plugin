import apiFetch from '@wordpress/api-fetch';
import {BlockControls, MediaPlaceholder} from "@wordpress/block-editor";
import {Notice, __experimentalInputControl as InputControl, __experimentalNumberControl as NumberControl} from "@wordpress/components";
import {useMemo, useState, useLayoutEffect} from "@wordpress/element";
import {_x} from "@wordpress/i18n";
import WOO_Portal_ImageResetControl from './controls/woo-portal-image-reset-control';

export default function Edit({attributes, setAttributes}) {
  const [isTypingUrl, setIsTypingUrl] = useState( false );
  const [mediaURL, setMediaURL] = useState( '' );

  const isValidURL = useMemo( () => {
      try {
        new URL( attributes.rest_uri );
        return true;
      } catch (error) {
        return false;
      }
    },
    [attributes.rest_uri]
  );

  useLayoutEffect( () => {
    if (0 !== attributes.image) {
      apiFetch( {path: `/wp/v2/media/${attributes.image}`} ).then( (response) => {
        const imageURL = response?.source_url || '';
        setMediaURL( imageURL );
      } );
    } else {
      setMediaURL( '' );
    }
  }, [attributes.image] );

  return (
    <>
      <BlockControls>
        <WOO_Portal_ImageResetControl
          isDisabled={ ! attributes.image }
          setAttributes={ setAttributes }
        />
      </BlockControls>

      <div className="woo-portal-search">
        <InputControl
          label={_x( 'URL to rest-endpoint or Domain URL', 'WOO Portal Search label', 'woo-portal-plugin' )}
          value={attributes.rest_uri}
          onChange={(rest_uri) => {
            setIsTypingUrl( true );
            setAttributes( {rest_uri} );
          }}
          onBlur={() => {
            setIsTypingUrl( false );
          }}
        />
        {!!attributes.rest_uri && !isTypingUrl && !isValidURL && (
          <Notice status="error">
            <p>
              {_x( 'Invalid URL', 'WOO Portal Search error', 'woo-portal-plugin' )}
            </p>
          </Notice>
        )}

        <InputControl
          label={_x( 'Title', 'WOO Portal Search label', 'woo-portal-plugin' )}
          value={attributes.title}
          onChange={(title) => setAttributes( {title} )}
        />
        <InputControl
          label={_x( 'Excerpt', 'WOO Portal Search label', 'woo-portal-plugin' )}
          value={attributes.excerpt}
          onChange={(excerpt) => setAttributes( {excerpt} )}
        />
        <InputControl
          label={_x( 'Search-field Placeholder', 'WOO Portal Search label', 'woo-portal-plugin' )}
          value={attributes.placeholder}
          onChange={(placeholder) => setAttributes( {placeholder} )}
        />

        <NumberControl
          label={_x( 'Results per page', 'WOO Portal Search label', 'woo-portal-plugin' )}
          isShiftStepEnabled={true}
          onChange={(per_page) => setAttributes( {per_page} )}
          shiftStep={10}
          value={attributes.per_page}
          min={4}
          max={32}
        />

        {mediaURL ? (
          <img src={mediaURL} alt="Media" />
        ) : (
          <MediaPlaceholder
            icon="format-image"
            onSelect={(media) => setAttributes( {image: media.id} )}
            accept="image/*"
            allowedTypes={['image']}
            multiple={false}
          />
        )}
      </div>
    </>
  );
}
