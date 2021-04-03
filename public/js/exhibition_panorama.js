//"場景id":[width,height,top,left]
var sceneBtnPosition = {
    "scene1": ["28%", "28%", "52%", "67%"],
    "scene1-1F_2": ["28%", "15.5%", "36%", "67%"],
    "scene1-1F_3": ["15%", "22%", "46%", "50%"],
    "scene1-1F_4": ["17%", "28%", "18%", "27%"],
    "scene1-1F_5": ["22%", "20%", "47%", "27%"],
    "scene1-3F_1": ["21%", "30%", "20%", "20%"],
    "scene1-3F_2": ["14%", "30%", "20%", "42%"],
    "scene1-3F_3": ["32%", "20%", "59%", "50%"],
    "scene1-3F_4": ["18%", "10%", "49%", "50%"],
    "scene1-3F_5": ["25%", "29%", "20%", "58%"],
    "scene1-5F_1": ["18%", "13%", "63%", "23%"],
    "scene1-5F_2": ["18%", "13%", "49.5%", "23%"],
    "scene1-5F_3": ["18%", "8%", "41.5%", "23%"],
    "scene1-5F_4": ["18%", "21%", "21%", "23%"],
    "scene1-5F_5": ["18%", "13%", "7%", "23%"],
    "scene1-5F_6": ["10%", "16%", "59%", "53%"],
    "scene1-5F_7": ["12%", "10%", "70%", "65%"],
    "scene1-5F_8": ["22%", "13%", "80%", "54%"],
    "scene1-5F_9": ["10%", "20%", "67%", "79%"],
    "scene1-5F_10": ["15%", "9%", "59%", "63%"],
    "scene2": ["8%", "20%", "65%", "46%"],
    "scene2-1F_2": ["18%", "25%", "60%", "28%"],
    "scene2-1F_3": ["12%", "26%", "34%", "27%"],
    "scene2-1F_4": ["13%", "17%", "17%", "33%"],
    "scene2-1F_5": ["11%", "18%", "16%", "54%"],
    "scene2-1F_6": ["11%", "24%", "33%", "61%"],
    "scene2-1F_7": ["11%", "16%", "58%", "59%"],
    "scene2-1F_8": ["15%", "22%", "39%", "43%"],
    "scene2-1F_9": ["11%", "16%", "83%", "64%"]
}
var sceneBtnPosition_floor = {
    "scene1": ["10%", "16%", "79%", "4%"],
    "scene1-3F_1": ["10%", "16%", "41%", "4%"],
    "scene1-5F_1": ["10%", "16%", "5%", "4%"]
}
// 5F的平面圖較高，所以新增一組
var sceneBtnPosition_floor5F = {
    "scene1": ["10%", "9%", "45%", "4%"],
    "scene1-3F_1": ["10%", "9%", "23.5%", "4%"]
}
var sceneBtnVr = {
    "scene1": "vr/tab01/B-01.html",
    "scene1-1F_2": "vr/tab01/B-02.html",
    "scene1-1F_3": "vr/tab01/B-03.html",
    "scene1-1F_4": "vr/tab01/B-05.html",
    "scene1-1F_5": "vr/tab01/B-04.html",
    "scene1-3F_1": "vr/tab01/C-02.html",
    "scene1-3F_2": "vr/tab01/C-01.html",
    "scene1-3F_3": "vr/tab01/C-04.html",
    "scene1-3F_4": "vr/tab01/C-03.html",
    "scene1-3F_5": "vr/tab01/C-05.html",
    "scene1-5F_1": "vr/tab01/D-01.html",
    "scene1-5F_2": "vr/tab01/D-02.html",
    "scene1-5F_3": "vr/tab01/D-03.html",
    "scene1-5F_4": "vr/tab01/D-04.html",
    "scene1-5F_5": "vr/tab01/D-05.html",
    "scene1-5F_6": "vr/tab01/D-11.html",
    "scene1-5F_7": "vr/tab01/D-09.html",
    "scene1-5F_8": "vr/tab01/D-10.html",
    "scene1-5F_9": "vr/tab01/D-07.html",
    "scene1-5F_10": "vr/tab01/D-06.html",
    "scene2": "vr/tab02/A-01.html",
    "scene2-1F_2": "vr/tab02/A-02.html",
    "scene2-1F_3": "vr/tab02/A-07.html",
    "scene2-1F_4": "vr/tab02/A-06.html",
    "scene2-1F_5": "vr/tab02/A-05.html",
    "scene2-1F_6": "vr/tab02/A-04.html",
    "scene2-1F_7": "vr/tab02/A-03.html",
    "scene2-1F_8": "vr/tab02/A-08.html",
    "scene2-1F_9": "vr/tab02/B-00.html",
}
var viewer;

function page_init() {
    //單頁獨立設定，loading結束後執行
    //執行順序3
    console.log("page_init");
    // ↓pannellum一定要在server上才能執行，且圖片寬度<=4096手機才能看
    viewer = pannellum.viewer('panorama', {
        "autoLoad": true,
        "showControls": false,
        "compass": false,
        "autoRotate": -2,
        "default": {
            "firstScene": "scene1",
            "sceneFadeDuration": 1000
        },
        //"hotSpotDebug": true,
        "scenes": {
            "scene1": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_1F_1.jpg",
                "hotSpots": [{
                        "pitch": -5,
                        "yaw": 6,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 7.4,
                        "yaw": 63,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 23.2,
                        "yaw": -74,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    },
                    {
                        "pitch": -10,
                        "yaw": -75,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "D"
                    }
                ]
            },
            "scene1-1F_2": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_1F_2.jpg",
                "hotSpots": [{
                        "pitch": 8.2,
                        "yaw": 174,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 9.8,
                        "yaw": 110,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 2.5,
                        "yaw": -43.9,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    },
                    {
                        "pitch": -1.5,
                        "yaw": -77,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "D"
                    },
                    {
                        "pitch": 13,
                        "yaw": -127,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "E"
                    },
                    {
                        "pitch": 10,
                        "yaw": -159,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "F"
                    }
                ]
            },
            "scene1-1F_3": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_1F_3.jpg",
                "hotSpots": [{
                        "pitch": -8.5,
                        "yaw": 3,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 1.8,
                        "yaw": 86,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    }
                ]
            },
            "scene1-1F_4": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_1F_4.jpg",
                "hotSpots": [{
                        "pitch": -8.2,
                        "yaw": -106,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": -8.3,
                        "yaw": 66.6,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": -7.9,
                        "yaw": 117.5,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    }
                ]
            },
            "scene1-1F_5": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_1F_5.jpg",
                "hotSpots": [{
                        "pitch": -19.3,
                        "yaw": -19.2,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 5.4,
                        "yaw": 64,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 9.6,
                        "yaw": 106.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    },
                    {
                        "pitch": 9.4,
                        "yaw": -161,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "D"
                    }
                ]
            },
            "scene1-3F_1": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_3F_1.jpg",
                "hotSpots": [{
                        "pitch": 5.1,
                        "yaw": -133.3,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 6.6,
                        "yaw": -118.6,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 7,
                        "yaw": -109.3,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    },
                    {
                        "pitch": 13.8,
                        "yaw": -80.1,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "D"
                    },
                    {
                        "pitch": 11.8,
                        "yaw": -44.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "E"
                    },
                    {
                        "pitch": 9.4,
                        "yaw": -1.7,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "F"
                    },
                    {
                        "pitch": 7.8,
                        "yaw": 84.6,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "G"
                    }
                ]
            },
            "scene1-3F_2": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_3F_2.jpg",
                "hotSpots": [{
                        "pitch": -11.8,
                        "yaw": -106.9,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 4.6,
                        "yaw": -10,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 6.8,
                        "yaw": 23,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    },
                    {
                        "pitch": 5.8,
                        "yaw": 50.6,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "D"
                    },
                    {
                        "pitch": 6.75,
                        "yaw": 66.1,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "E"
                    },
                    {
                        "pitch": 7.5,
                        "yaw": 94.5,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "F"
                    },
                    {
                        "pitch": 5.7,
                        "yaw": 123.3,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "G"
                    },
                    {
                        "pitch": 4.8,
                        "yaw": 136.3,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "H"
                    }
                ]
            },
            "scene1-3F_3": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_3F_3.jpg",
                "hotSpots": [{
                        "pitch": 6.6,
                        "yaw": -161.4,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 9.3,
                        "yaw": 176,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 8.9,
                        "yaw": 152.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    },
                    {
                        "pitch": -39.6,
                        "yaw": 145.4,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "D"
                    },
                    {
                        "pitch": 6,
                        "yaw": 105.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "E"
                    },
                    {
                        "pitch": 11,
                        "yaw": 19,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "F"
                    }
                ]
            },
            "scene1-3F_4": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_3F_4.jpg",
                "hotSpots": [{
                        "pitch": 8.6,
                        "yaw": 156.9,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 6.5,
                        "yaw": 140.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": -14,
                        "yaw": 123.4,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    },
                    {
                        "pitch": 5.5,
                        "yaw": 105.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "D"
                    },
                    {
                        "pitch": 9.1,
                        "yaw": 68.6,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "E"
                    }
                ]
            },
            "scene1-3F_5": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_3F_5.jpg",
                "hotSpots": [{
                        "pitch": 0.6,
                        "yaw": 124.9,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 24.6,
                        "yaw": -7.1,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": -9.7,
                        "yaw": -129.7,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    }
                ]
            },
            "scene1-5F_1": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_5F_1.jpg",
                "hotSpots": [{
                        "pitch": 17.1,
                        "yaw": -122.4,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 11,
                        "yaw": -83.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 12.3,
                        "yaw": -2.1,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    },
                    {
                        "pitch": 13.1,
                        "yaw": 108.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "D"
                    }
                ]
            },
            "scene1-5F_2": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_5F_2.jpg",
                "hotSpots": [{
                        "pitch": -20.1,
                        "yaw": -1.9,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": -16,
                        "yaw": -108,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": -19.7,
                        "yaw": 104.4,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    }
                ]
            },
            "scene1-5F_3": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_5F_3.jpg",
                "hotSpots": [{
                        "pitch": 8.2,
                        "yaw": -96.5,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 9.8,
                        "yaw": -37.7,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 6.6,
                        "yaw": 55.3,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    },
                    {
                        "pitch": 4.6,
                        "yaw": 124.1,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "D"
                    }
                ]
            },
            "scene1-5F_4": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_5F_4.jpg",
                "hotSpots": [{
                        "pitch": 3.3,
                        "yaw": -10.9,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 3.3,
                        "yaw": 25.5,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": -40,
                        "yaw": -34,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    },
                    {
                        "pitch": -20.6,
                        "yaw": 92.5,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "D"
                    },
                    {
                        "pitch": -4.6,
                        "yaw": 134.2,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "E"
                    }
                ]
            },
            "scene1-5F_5": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_5F_5.jpg",
                "hotSpots": [{
                        "pitch": -11.1,
                        "yaw": -43,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": -9.9,
                        "yaw": -10.1,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 8.6,
                        "yaw": 86.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    }
                ]
            },
            "scene1-5F_6": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_5F_6.jpg",
                "hotSpots": [{
                    "pitch": 9.3,
                    "yaw": 138.6,
                    "cssClass": "custom-hotspot",
                    "createTooltipFunc": hotspot,
                    "createTooltipArgs": "A"
                }]
            },
            "scene1-5F_7": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_5F_7.jpg",
                "hotSpots": [{
                        "pitch": 0.6,
                        "yaw": -7.3,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 4.2,
                        "yaw": 36,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 1.9,
                        "yaw": 84.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    },
                    {
                        "pitch": 1.6,
                        "yaw": 130.4,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "D"
                    },
                    {
                        "pitch": 6.1,
                        "yaw": -124.4,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "E"
                    }
                ]
            },
            "scene1-5F_8": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_5F_8.jpg",
                "hotSpots": [{
                        "pitch": 13.3,
                        "yaw": 71.4,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 8.3,
                        "yaw": 19.5,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 12.5,
                        "yaw": -73.9,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    },
                    {
                        "pitch": 8,
                        "yaw": -103.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "D"
                    }
                ]
            },
            "scene1-5F_9": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_5F_9.jpg",
                "hotSpots": [{
                        "pitch": 4.3,
                        "yaw": 112.2,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 24.2,
                        "yaw": -156.5,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 10.8,
                        "yaw": -47.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    }
                ]
            },
            "scene1-5F_10": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab01/panorama_5F_10.jpg",
                "hotSpots": [{
                        "pitch": 20.3,
                        "yaw": 141.1,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 13.1,
                        "yaw": -0.9,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": 10.1,
                        "yaw": -91.9,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    }
                ]
            },
            "scene2": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab02/panorama_1F_1.jpg",
                "hotSpots": [{
                    "pitch": 15,
                    "yaw": -4.6,
                    "cssClass": "custom-hotspot",
                    "createTooltipFunc": hotspot,
                    "createTooltipArgs": "A"
                }]
            },
            "scene2-1F_2": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab02/panorama_1F_2.jpg",
                "hotSpots": [{
                    "pitch": 16.9,
                    "yaw": 61.5,
                    "cssClass": "custom-hotspot",
                    "createTooltipFunc": hotspot,
                    "createTooltipArgs": "A"
                }]
            },
            "scene2-1F_3": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab02/panorama_1F_3.jpg",
                "hotSpots": [{
                        "pitch": -8.3,
                        "yaw": -41.3,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 2.1,
                        "yaw": -165.8,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    },
                    {
                        "pitch": -11.1,
                        "yaw": 101.4,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "C"
                    }
                ]
            },
            "scene2-1F_4": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab02/panorama_1F_4.jpg",
                "hotSpots": [{
                    "pitch": -36.5,
                    "yaw": 41.1,
                    "cssClass": "custom-hotspot",
                    "createTooltipFunc": hotspot,
                    "createTooltipArgs": "A"
                }]
            },
            "scene2-1F_5": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab02/panorama_1F_5.jpg",
                "hotSpots": [{
                    "pitch": -49.3,
                    "yaw": 29.4,
                    "cssClass": "custom-hotspot",
                    "createTooltipFunc": hotspot,
                    "createTooltipArgs": "A"
                }]
            },
            "scene2-1F_6": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab02/panorama_1F_6.jpg",
                "hotSpots": [{
                    "pitch": -28.9,
                    "yaw": -21.3,
                    "cssClass": "custom-hotspot",
                    "createTooltipFunc": hotspot,
                    "createTooltipArgs": "A"
                }]
            },
            "scene2-1F_7": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab02/panorama_1F_7.jpg",
                "hotSpots": [{
                    "pitch": -40.5,
                    "yaw": 1.1,
                    "cssClass": "custom-hotspot",
                    "createTooltipFunc": hotspot,
                    "createTooltipArgs": "A"
                }]
            },
            "scene2-1F_8": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab02/panorama_1F_8.jpg",
                "hotSpots": [{
                    "pitch": 21.4,
                    "yaw": -0.1,
                    "cssClass": "custom-hotspot",
                    "createTooltipFunc": hotspot,
                    "createTooltipArgs": "A"
                }]
            },
            "scene2-1F_9": {
                "type": "equirectangular",
                "panorama": "../images/exhibition/tab02/panorama_1F_9.jpg",
                "hotSpots": [{
                        "pitch": 5,
                        "yaw": 80,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "A"
                    },
                    {
                        "pitch": 19.4,
                        "yaw": 0,
                        "cssClass": "custom-hotspot",
                        "createTooltipFunc": hotspot,
                        "createTooltipArgs": "B"
                    }
                ]
            }
        }
    });

    document.getElementById('zoom-in').addEventListener('click', function(e) {
        viewer.setHfov(viewer.getHfov() - 10);
    });
    document.getElementById('zoom-out').addEventListener('click', function(e) {
        viewer.setHfov(viewer.getHfov() + 10);
    });
    document.getElementById('fullscreen').addEventListener('click', function(e) {
        viewer.toggleFullscreen();
    });
    $(".main-panoramaBox-menu li").click(function() {
        if (!$(this).hasClass("act")) {
            $(this).parent().find(".act").removeClass("act");
            _index = Number($(this).index()) + 1;
            $(this).addClass("act");
            //切換panorama
            change_scene("scene" + _index);
        }
    });
    $(".panoramaBox-content-intro-img-btnBox a").click(function() {
        //切換panorama
        _tmp = $(this).attr("data-scene");
        change_scene(_tmp);
    });
    //設定按鈕位置
    $(".panoramaBox-content-intro-img-btnBox").each(function(index, el) {
        if (index == 0) {
            _tabindex = 0;
        } else {
            _tabindex = -1;
        }
        $("a", this).each(function(index, el) {
            _tmp = $(this).attr("data-scene");
            if ($(this).hasClass("floor")) {
                _checkstr = $(this).parent().parent().parent().attr("id");
                if (_checkstr.indexOf("5F") >= 0) {
                    _array = sceneBtnPosition_floor5F[_tmp];
                } else {
                    _array = sceneBtnPosition_floor[_tmp];
                }
            } else {
                _array = sceneBtnPosition[_tmp];
            }
            $(this).css({
                "width": _array[0],
                "height": _array[1],
                "top": _array[2],
                "left": _array[3]
            });
            $(this).attr('tabindex', _tabindex);
        });
    });
    //更新VR網址
    $("#video-vr").attr('href', sceneBtnVr["scene1"]);
}

function page_resize() {
    //單頁獨立設定，resize時執行
    console.log("page_resize");
}
//---------------------------------
// Hot spot creation function
function hotspot(hotSpotDiv, args) {
    hotSpotDiv.classList.add('custom-tooltip');
    var span = document.createElement('span');
    span.innerHTML = args;
    hotSpotDiv.appendChild(span);
}

function change_scene(_str) {
    //切換panorama
    viewer.loadScene(_str);
    //切換平面圖，變更tabindex
    //關閉
    _target = $(".main-panoramaBox-content").find(".act");
    $("a", _target).each(function(index, el) {
        $(this).attr('tabindex', -1);
    });
    _target.removeClass("act");
    //開啟
    _target = $("#" + _str);
    $("a", _target).each(function(index, el) {
        $(this).attr('tabindex', 0);
    });
    _target.addClass("act");
    //更新VR網址
    $("#video-vr").attr('href', sceneBtnVr[_str]);
}
