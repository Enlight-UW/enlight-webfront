//This is the Knockout viewmodel that all dynamic elements of the page will
//reference - this gets updated by the KO Mapping plugin - we'll just define
//the default values here.
var defaultData = {
    "error": false,
    "errormessage": "No error",
    "fountainState": "Initializing Webfront...",
    "valveState": 0,
    "restrictState": 0,
    "LMOC": false,
    "apiItems": [{
        "apiKeyItem": "This is an API Key!",
        "apiNameItem": "This is an API Name!",
        "apiPriorityItem": 69
        
    }, {
        "apiKeyItem": "Second api key",
        "apiNameItem": "Another name",
        "apiPriorityItem": 666
    }]
}
