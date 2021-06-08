import {yasrDrawRankings} from "./ranking";

const { __ } = wp.i18n; // Import __() from wp.i181n
const arrayClasses = ['yasr-rater-stars', 'yasr-rater-stars-vv', 'yasr-multiset-visitors-rater'];

/*** Constant used by yasr
yasrCommonData (postid, ajaxurl, loggedUser, visitorStatsEnabled, loaderHtml, tooltipValues', isrtl)
***/

for (let i=0; i<arrayClasses.length; i++) {
    //Search and set all div with class yasr-multiset-visitors-rater
    yasrSearchStarsDom(arrayClasses[i]);
}

//Drow Rankings
yasrDrawRankings();


/**
 * Search for divs with defined classname
 */
export function yasrSearchStarsDom (starsClass) {
    //At pageload, check if there is some shortcode with class yasr-rater-stars
    const yasrRaterInDom = document.getElementsByClassName(starsClass);
    //If so, call the function to set the rating
    if (yasrRaterInDom.length > 0) {
        //stars class for most shortcodes
        if(starsClass === 'yasr-rater-stars' || starsClass === 'yasr-ranking-stars') {
            yasrSetRating(yasrRaterInDom);
        }

        if(starsClass === 'yasr-rater-stars-vv') {
            yasrVisitorVotesFront(yasrRaterInDom);
            if (yasrCommonData.visitorStatsEnabled === 'yes') {
                let yasrStatsInDom = document.getElementsByClassName('yasr-dashicons-visitor-stats');
                if (yasrStatsInDom) {
                    yasrDrawTipsProgress (yasrStatsInDom);
                }
            }
        }

        if (starsClass === 'yasr-multiset-visitors-rater') {
            yasrRaterVisitorsMultiSet(yasrRaterInDom)
        }
    }
}

/****** Tooltip function ******/

//used in shortcode page and ajax page
function yasrDrawTipsProgress (yasrStatsInDom) {

    //htmlcheckid declared false
    let htmlIdChecked = false;

    let txtContainer;  //the container of the text [TOTAL...];  needed to get the text color
    let computedcolor;

    for (let i = 0; i < yasrStatsInDom.length; i++) {
        (function (i) {

            let htmlId = '#'+yasrStatsInDom.item(i).id;
            let postId = yasrStatsInDom.item(i).getAttribute('data-postid');

            //get the font color from yasr-vv-text-container only the first time
            if(i===0) {
                //main container
                txtContainer     = document.getElementsByClassName('yasr-vv-text-container');
                if(txtContainer !== null) {
                    computedcolor = window.getComputedStyle(txtContainer[0], null).getPropertyValue("color");
                }
            }

            //if computed color exists, change the color to the svg
            if(computedcolor) {
                //get the svg element
                let svg = document.getElementById(yasrStatsInDom.item(i).id);
                //fill with the same color of the text
                svg.style.fill=computedcolor;
            }

            let data = {
                action: 'yasr_stats_visitors_votes',
                post_id: postId
            };

            let initialContent = '<span style="color: #0a0a0a">Loading...</span>';

            tippy(htmlId, {
                content: initialContent,
                theme: 'yasr',
                arrow: 'true',
                arrowType: 'round',

                //When support for IE will be dropped out, this will become onShow(tip)
                onShow: function onShow(tip) {
                    if (htmlId !== htmlIdChecked) {
                        //must be post or wont work
                        jQuery.post(yasrCommonData.ajaxurl, data, function (response) {
                            response = JSON.parse(response);
                            tip.setContent(response);
                        });
                    }
                },
                onHidden: function onHidden() {
                    htmlIdChecked = htmlId;
                }

            });

        })(i);
    }

}

/****** End tooltipfunction ******/

//this is the function that print the overall rating shortcode, get overall rating and starsize
export function yasrSetRaterValue (starSize, htmlId, element=false, step=0.1, readonly=true,
                            rating=false, rateCallback=false) {
    let domElement;
    if(element) {
        domElement = element;
    } else {
        domElement = document.getElementById(htmlId)
    }

    //convert to be a number
    starSize = parseInt(starSize);

    raterJs({
        starSize: starSize,
        showToolTip: false,
        element: domElement,
        step: step,
        readOnly: readonly,
        rating: rating,
        rateCallback: rateCallback
    });

}

function yasrSetRating (yasrRatingsInDom) {

    //Check in the object
    for (let i = 0; i < yasrRatingsInDom.length; i++) {
        //yasr-star-rating is the class set by rater.js : so, if already exists,
        //means that rater already run for the element
        if(yasrRatingsInDom.item(i).classList.contains('yasr-star-rating') === false) {
            const element  = yasrRatingsInDom.item(i);
            const htmlId   = element.id;
            const starSize = element.getAttribute('data-rater-starsize');
            yasrSetRaterValue(starSize, htmlId, element);
        }
    }

}

function yasrVisitorVotesFront (yasrRaterVVInDom) {

    //Check in the object
    for (let i = 0; i < yasrRaterVVInDom.length; i++) {

        (function(i) {
            //yasr-star-rating is the class set by rater.js : so, if already exists,
            //means that rater already run for the element
            if(yasrRaterVVInDom.item(i).classList.contains('yasr-star-rating') !== false) {
                return;
            }

            const elem            = yasrRaterVVInDom.item(i);
            let rating            = elem.getAttribute('data-rating');
            let readonlyShortcode = elem.getAttribute('data-readonly-attribute');
            let readonly          = elem.getAttribute('data-rater-readonly');

            if (readonlyShortcode === null) {
                readonlyShortcode = false;
            }

            readonlyShortcode = yasrTrueFalseStringConvertion(readonlyShortcode);
            readonly          = yasrTrueFalseStringConvertion(readonly);

            //if comes from shortcode attribute, and is true, readonly is always true
            if (readonlyShortcode === true) {
                readonly = true;
            }

            let postId        = elem.getAttribute('data-rater-postid');
            let htmlId        = elem.id;
            let uniqueId      = htmlId.replace('yasr-visitor-votes-rater-', '');
            let starSize      = parseInt(elem.getAttribute('data-rater-starsize'));
            let nonce         = elem.getAttribute('data-rater-nonce');
            let isSingular    = elem.getAttribute('data-issingular');

            let containerVotesNumberName   = 'yasr-vv-votes-number-container-' + uniqueId;
            let containerAverageNumberName = 'yasr-vv-average-container-' + uniqueId;
            let bottomContainerName        = 'yasr-vv-bottom-container-' + uniqueId;
            const containerVotesNumber     = document.getElementById(containerVotesNumberName);
            const containerAverageNumber   = document.getElementById(containerAverageNumberName);
            const bottomContainer          = document.getElementById(bottomContainerName);

            let loaderContainer  = 'yasr-vv-loader-' + uniqueId;

            if(yasrCommonData.ajaxEnabled === 'yes') {
                let cpt = elem.getAttribute('data-cpt');

                if(cpt === '') {
                    cpt = 'posts';
                }

                let urlVisitorVotes = 'wp/v2/'+ cpt +'/' + postId + '?_fields=yasr_visitor_votes&_wpnonce='+yasrCommonData.nonce;

                jQuery.get(yasrCommonData.restEndpoint + urlVisitorVotes).done(
                    function (data) {
                        let readonly;
                        //if has readonly attribute, it is always true
                        if(readonlyShortcode === true) {
                            readonly = true;
                        } else {
                            readonly = data.yasr_visitor_votes.stars_attributes.read_only;
                        }

                        if (data.yasr_visitor_votes.number_of_votes > 0) {
                            rating = data.yasr_visitor_votes.sum_votes / data.yasr_visitor_votes.number_of_votes;
                        } else {
                            rating = 0;
                        }
                        rating   = rating.toFixed(1);
                        rating   = parseFloat(rating);

                        yasrSetVisitorVotesRater(starSize, rating, postId, readonly, htmlId, uniqueId, nonce, isSingular,
                            containerVotesNumber, containerAverageNumber,  loaderContainer, bottomContainer);

                        //do this only if yasr_visitor_votes has not the readonly attribute
                        if(readonlyShortcode !== true) {

                            if(containerVotesNumber !== null) {
                                containerVotesNumber.innerHTML = data.yasr_visitor_votes.number_of_votes;
                            }
                            if(containerAverageNumber !== null) {
                                containerAverageNumber.innerHTML = rating;
                            }

                            //insert span with text after the average
                            if(data.yasr_visitor_votes.stars_attributes.span_bottom !== false
                                && bottomContainer !== null) {
                                    bottomContainer.innerHTML     = data.yasr_visitor_votes.stars_attributes.span_bottom;
                                    bottomContainer.style.display = '';
                            }
                        }

                }).fail(
                    function(e, x, settings, exception) {
                        console.info(__('YASR ajax call failed. Showing ratings from html', 'yet-another-stars-rating'));
                        yasrSetVisitorVotesRater(starSize, rating, postId, readonly, htmlId, uniqueId, nonce, isSingular,
                            containerVotesNumber, containerAverageNumber,  loaderContainer, bottomContainer);

                        //Unhide the div below the stars
                        if(readonlyShortcode !== true) {
                            bottomContainer.style.display = '';
                        }
                    });
            } else {
                yasrSetVisitorVotesRater(starSize, rating, postId, readonly, htmlId, uniqueId, nonce, isSingular,
                    containerVotesNumber, containerAverageNumber,  loaderContainer, bottomContainer);
            }

        })(i);

    }//End for

}

function yasrSetVisitorVotesRater (starSize, rating, postId, readonly, htmlId, uniqueId, nonce, isSingular,
                                   containerVotesNumber, containerAverageNumber, loaderContainer, bottomContainer) {

    //Be sure is a number and not a string
    rating = parseFloat(rating);

    //raterjs accepts only boolean for readOnly element
    readonly = yasrTrueFalseStringConvertion(readonly);

    const elem = document.getElementById(htmlId);

    let rateCallback = function (rating, done) {
        //show the loader
        document.getElementById(loaderContainer).innerHTML = yasrCommonData.loaderHtml;

        //Creating an object with data to send
        var data = {
            action: 'yasr_send_visitor_rating',
            rating: rating,
            post_id: postId,
            nonce_visitor: nonce,
            is_singular : isSingular
        };

        this.setRating(rating);
        this.disable();

        //Send value to the Server
        jQuery.post(yasrCommonData.ajaxurl, data, function (response) {
            //decode json
            response = JSON.parse(response);
            //hide the loader
            if(containerVotesNumber !== null) {
                containerVotesNumber.innerHTML   = response.number_of_votes;
            }
            if(containerAverageNumber !== null) {
                containerAverageNumber.innerHTML = response.average_rating;
            }
            if(bottomContainer !== null) {
                bottomContainer.innerHTML = response.rating_saved_text;
            }

            //empty loader gif
            document.getElementById(loaderContainer).innerHTML = '';

        });
        done();
    }

    yasrSetRaterValue (starSize, htmlId, elem, 1, readonly, rating, rateCallback);

}

function yasrRaterVisitorsMultiSet (yasrMultiSetVisitorInDom) {
    //will have field id and vote
    var ratingObject = "";

    //an array with all the ratings objects
    var ratingArray = [];

    //Check in the object
    for (let i = 0; i < yasrMultiSetVisitorInDom.length; i++) {
        (function (i) {
            //yasr-star-rating is the class set by rater.js : so, if already exists,
            //means that rater already run for the element
            if(yasrMultiSetVisitorInDom.item(i).classList.contains('yasr-star-rating') !== false) {
                return;
            }

            let elem     = yasrMultiSetVisitorInDom.item(i);
            let htmlId   = elem.id;
            let readonly = elem.getAttribute('data-rater-readonly');

            readonly = yasrTrueFalseStringConvertion(readonly);

            const rateCallback = function (rating, done) {
                const postId     = elem.getAttribute('data-rater-postid');
                const setId      = elem.getAttribute('data-rater-setid');
                const setIdField = elem.getAttribute('data-rater-set-field-id');

                //Just leave 1 number after the .
                rating = rating.toFixed(1);
                //Be sure is a number and not a string
                const vote = parseInt(rating);

                this.setRating(vote); //set the new rating

                ratingObject = {
                    postid: postId,
                    setid: setId,
                    field: setIdField,
                    rating: vote
                };

                //creating rating array
                ratingArray.push(ratingObject);

                done();

            }

            yasrSetRaterValue (16, htmlId, elem, 1, readonly, false, rateCallback);

        })(i);

    }

    jQuery('.yasr-send-visitor-multiset').on('click', function() {
        const multiSetPostId = this.getAttribute('data-postid');
        const multiSetId     = this.getAttribute('data-setid');
        const nonce          = this.getAttribute('data-nonce');

        jQuery('#yasr-send-visitor-multiset-'+multiSetPostId+'-'+multiSetId).hide();
        jQuery('#yasr-loader-multiset-visitor-'+multiSetPostId+'-'+multiSetId).show();

        var data = {
            action: 'yasr_visitor_multiset_field_vote',
            nonce: nonce,
            post_id: multiSetPostId,
            rating: ratingArray,
            set_type: multiSetId
        };

        //Send value to the Server
        jQuery.post(yasrCommonData.ajaxurl, data, function(response) {
            jQuery('#yasr-loader-multiset-visitor-'+multiSetPostId+'-'+multiSetId).text(response);
        });

    });
    
} //End function

function yasrTrueFalseStringConvertion(string) {

    if (typeof string === 'undefined' || string === null || string === '') {
        string = true;
    }

    //Convert string to boolean
    if (string === 'true' || string === '1') {
        string = true;
    }
    if (string === 'false' || string === '0') {
        string = false;
    }

    return string;

}
