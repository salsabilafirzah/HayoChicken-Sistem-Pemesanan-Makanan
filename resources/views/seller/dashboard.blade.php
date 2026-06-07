<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
  <title>Hayo Chicken – Panel Penjual</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">
  <style>
    :root {
      --red: #9E090F;
      --redl: #C20B12;
      --yellow: #FFB21E;
      --cream: #F8EFDE;
      --creamd: #EFE0C4;
      --w: #FFFFFF;
      --g1: #F5F5F5;
      --g2: #E8E8E8;
      --g4: #AAAAAA;
      --g6: #666666;
      --g8: #333333;
      --bk: #111111;
      --sh0: 0 2px 8px rgba(0, 0, 0, .08);
      --sh1: 0 4px 20px rgba(0, 0, 0, .12);
      --sh2: 0 8px 40px rgba(0, 0, 0, .18);
      --r1: 10px;
      --r2: 16px;
      --r3: 24px;
      --r4: 32px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      -webkit-tap-highlight-color: transparent
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--red);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      min-height: 100dvh;
      overflow: hidden
    }

    /* APP SHELL — sama seperti halaman lain */
    .app {
      width: 100%;
      max-width: 420px;
      height: 100vh;
      height: 100dvh;
      background: var(--cream);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }

    .screen {
      display: none;
      flex-direction: column;
      flex: 1;
      overflow: hidden;
      position: relative;
      background: var(--cream);
    }

    .screen.on {
      display: flex
    }

    /* DESKTOP — sama seperti halaman lain */
    @media (min-width: 480px) {
      body {
        background: radial-gradient(circle, #b81419 0%, #680507 100%);
      }
      .app {
        height: 850px;
        border-radius: 40px;
        border: 8px solid rgba(255,255,255,0.1);
        box-shadow: 0 20px 50px rgba(0,0,0,0.4);
      }
    }

    .scroll {
      flex: 1;
      overflow-y: auto;
      overflow-x: hidden;
      scrollbar-width: none;
      -webkit-overflow-scrolling: touch
    }

    .scroll::-webkit-scrollbar {
      display: none
    }

    /* ─── AUTH ─── */
    .auth-hdr {
      background: var(--red);
      padding: 56px 24px 36px;
      border-radius: 0 0 var(--r4) var(--r4);
      position: relative;
      flex-shrink: 0
    }

    .back-circ {
      position: absolute;
      top: 52px;
      left: 20px;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .2);
      border: none;
      color: #fff;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center
    }

    .auth-logo-sm {
      width: 48px;
      height: 48px;
      object-fit: contain;
      filter: brightness(0) invert(1);
      display: block;
      margin: 0 auto 10px
    }

    .auth-h1 {
      color: #fff;
      font-size: 26px;
      font-weight: 800;
      text-align: center
    }

    .auth-sub {
      color: rgba(255, 255, 255, .7);
      font-size: 13px;
      text-align: center;
      margin-top: 4px
    }

    .auth-body {
      flex: 1;
      padding: 28px 24px;
      overflow-y: auto
    }

    .fg {
      margin-bottom: 14px
    }

    .flbl {
      font-size: 11px;
      font-weight: 700;
      color: var(--g6);
      margin-bottom: 5px;
      display: block;
      text-transform: uppercase;
      letter-spacing: .6px
    }

    .fwrap {
      position: relative
    }

    .finput {
      width: 100%;
      padding: 13px 13px 13px 42px;
      border-radius: var(--r1);
      border: 2px solid var(--g2);
      background: #fff;
      font-family: 'Inter', sans-serif;
      font-size: 14px;
      color: var(--bk);
      outline: none;
      transition: border-color .2s
    }

    .finput:focus {
      border-color: var(--red)
    }

    .finput.np {
      padding-left: 13px
    }

    .fic {
      position: absolute;
      left: 13px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--g4);
      display: flex
    }

    .btn-main {
      width: 100%;
      padding: 15px;
      border-radius: 50px;
      border: none;
      background: var(--red);
      color: #fff;
      font-family: 'Inter', sans-serif;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      transition: all .2s;
      margin-top: 8px;
      box-shadow: 0 4px 20px rgba(158, 9, 15, .35)
    }

    .btn-main:active {
      transform: scale(.97)
    }

    .btn-main.dark {
      background: var(--g8);
      box-shadow: none
    }

    .btn-main.outline {
      background: transparent;
      border: 2px solid var(--red);
      color: var(--red);
      box-shadow: none
    }

    .forgot {
      text-align: right;
      margin-bottom: 6px
    }

    .forgot a {
      color: var(--red);
      font-size: 12px;
      font-weight: 600;
      cursor: pointer
    }

    /* ─── PAGE HEADER ─── */
    .phdr {
      background: var(--red);
      padding: 52px 20px 20px;
      border-radius: 0 0 var(--r4) var(--r4);
      flex-shrink: 0
    }

    .phdr-row {
      display: flex;
      align-items: center;
      gap: 12px
    }

    .ptitle {
      color: #fff;
      font-size: 21px;
      font-weight: 800
    }

    .pbtn {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .2);
      border: none;
      color: #fff;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0
    }

    /* ─── BOTTOM NAV ─── */
    .bnav {
      display: flex;
      background: #fff;
      border-top: 1px solid var(--g2);
      padding: 8px 0 20px;
      flex-shrink: 0
    }

    .ni {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 3px;
      padding: 6px 0;
      cursor: pointer;
      border: none;
      background: transparent;
      transition: transform .15s
    }

    .ni:active {
      transform: scale(.88)
    }

    .niwrap {
      position: relative;
      display: flex;
      width: 44px;
      height: 44px;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: background .2s
    }

    .nsvg {
      color: var(--red);
      transition: color .2s
    }

    .nlbl {
      font-size: 9px;
      font-weight: 600;
      color: var(--g6);
      transition: color .2s;
      margin-top: 2px
    }

    .seller-nav .ni.on .niwrap {
      background: var(--red)
    }

    .seller-nav .ni.on .nsvg {
      color: #fff
    }

    .seller-nav .ni.on .nlbl {
      color: var(--red)
    }

    /* ─── BADGE ─── */
    .badge {
      padding: 4px 10px;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 700;
      display: inline-block
    }

    .bnew {
      background: rgba(255, 178, 30, .18);
      color: #9A6200
    }

    .bproc {
      background: rgba(158, 9, 15, .1);
      color: var(--red)
    }

    .bship {
      background: rgba(30, 118, 210, .1);
      color: #1E76D2
    }

    .bdone {
      background: rgba(39, 174, 96, .12);
      color: #1E9E52
    }

    .bcancel {
      background: rgba(150, 150, 150, .12);
      color: var(--g6)
    }

    /* ─── SELLER SCREENS ─── */
    #seller {
      background: var(--cream)
    }

    .shdr {
      background: var(--red);
      padding: 56px 22px 24px;
      border-radius: 0 0 36px 36px;
      flex-shrink: 0;
      position: relative
    }

    .shdr-top {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 20px
    }

    .sgreet {
      color: rgba(255, 255, 255, .75);
      font-size: 11px;
      font-weight: 500;
      letter-spacing: .2px
    }

    .sname {
      color: #fff;
      font-size: 22px;
      font-weight: 800;
      margin-top: 2px
    }

    .sgrid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      grid-template-rows: auto auto;
      padding: 0 4px
    }

    .scard {
      background: rgba(255, 255, 255, .18);
      border-radius: 18px;
      padding: 14px 14px 12px;
      backdrop-filter: blur(8px)
    }

    .sic {
      color: rgba(255, 255, 255, .9);
      margin-bottom: 6px;
      display: flex
    }

    .sval {
      color: #fff;
      font-size: 24px;
      font-weight: 900;
      line-height: 1
    }

    .slbl {
      color: rgba(255, 255, 255, .7);
      font-size: 10px;
      margin-top: 3px;
      font-weight: 500
    }

    .ibtn {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .2);
      border: none;
      color: #fff;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      flex-shrink: 0
    }

    .stabs {
      display: flex;
      padding: 16px 20px 4px;
      gap: 8px;
      flex-shrink: 0;
      background: var(--cream)
    }

    .stab {
      padding: 8px 20px;
      border-radius: 999px;
      border: 1.5px solid var(--g2);
      background: #fff;
      font-family: 'Inter', sans-serif;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      color: var(--g6);
      transition: all .2s;
      white-space: nowrap
    }

    .stab.on {
      background: var(--red);
      border-color: var(--red);
      color: #fff;
      box-shadow: 0 4px 12px rgba(158, 9, 15, .3)
    }

    .ocard {
      background: #fff;
      border-radius: 20px;
      padding: 16px;
      margin-bottom: 10px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
      cursor: pointer;
      transition: transform .15s
    }

    .ocard:active {
      transform: scale(.97)
    }

    .ocard-top {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 5px
    }

    .oid {
      font-size: 11px;
      color: var(--g4);
      font-weight: 500
    }

    .ocust {
      font-size: 15px;
      font-weight: 800;
      color: var(--bk);
      margin-bottom: 5px
    }

    .oitems {
      font-size: 12px;
      color: var(--g6);
      margin-bottom: 8px;
      line-height: 1.5
    }

    .oaddr {
      display: flex;
      align-items: flex-start;
      gap: 4px;
      font-size: 11px;
      color: var(--g6);
      margin-bottom: 10px;
      line-height: 1.4
    }

    .ofoot {
      display: flex;
      justify-content: space-between;
      align-items: center
    }

    .ototl {
      font-size: 15px;
      font-weight: 800;
      color: var(--red)
    }

    .bsm {
      padding: 8px 18px;
      border-radius: 999px;
      border: none;
      font-family: 'Inter', sans-serif;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: all .2s
    }

    .bsm.r {
      background: var(--red);
      color: #fff;
      box-shadow: 0 3px 10px rgba(158, 9, 15, .3)
    }

    .bsm.o {
      background: transparent;
      border: 2px solid var(--red);
      color: var(--red)
    }

    .bsm.g {
      background: rgba(39, 174, 96, .1);
      border: 2px solid #27AE60;
      color: #27AE60
    }

    .bsm.gr {
      background: rgba(100, 100, 100, .1);
      border: 2px solid var(--g4);
      color: var(--g6)
    }

    .bsm:active {
      transform: scale(.93)
    }

    .smcard {
      display: flex;
      gap: 12px;
      background: #fff;
      border-radius: 20px;
      padding: 14px;
      margin-bottom: 10px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
      align-items: center
    }

    .smthumb {
      width: 60px;
      height: 60px;
      border-radius: 14px;
      background: var(--cream);
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      border: 1px solid var(--creamd)
    }

    .sminfo {
      flex: 1;
      min-width: 0
    }

    .smname {
      font-size: 14px;
      font-weight: 700;
      color: var(--bk)
    }

    .smprice {
      font-size: 13px;
      color: var(--red);
      font-weight: 700;
      margin-top: 3px
    }

    .smstock {
      font-size: 11px;
      color: var(--g4);
      margin-top: 2px;
      font-weight: 500
    }

    .smstock.out {
      color: #C0392B;
      font-weight: 700
    }

    .seller-nav {
      display: flex;
      background: var(--creamd);
      border-top: none;
      padding: 10px 0 22px;
      flex-shrink: 0;
      border-radius: 28px 28px 0 0;
      box-shadow: 0 -4px 20px rgba(0, 0, 0, .08)
    }

    .fab {
      position: absolute;
      bottom: 110px;

      right: 20px;
      width: 54px;
      height: 54px;
      border-radius: 50%;
      background: var(--yellow);
      border: none;
      color: var(--red);
      cursor: pointer;
      box-shadow: 0 6px 20px rgba(255, 178, 30, .6);
      align-items: center;
      justify-content: center;
      z-index: 20;
      transition: transform .15s;
      display: none
    }

    .fab.show {
      display: flex
    }

    .fab:active {
      transform: scale(.88)
    }

    /* ─── SELLER ORDER DETAIL ─── */
    #seller-order-detail {
      background: var(--cream)
    }

    .order-det-header {
      background: var(--red);
      padding: 52px 20px 24px;
      border-radius: 0 0 var(--r4) var(--r4);
      flex-shrink: 0
    }

    .order-det-id {
      color: rgba(255, 255, 255, .75);
      font-size: 12px;
      margin-bottom: 4px;
      padding-left: 44px;
    }


    .order-det-name {
      color: #fff;
      font-size: 20px;
      font-weight: 800;
      padding-left: 44px;
    }


    .order-det-badge {
      margin-top: 6px;
      padding-left: 44px;
    }


    .det-item-row {
      display: flex;
      gap: 10px;
      background: #fff;
      border-radius: var(--r2);
      padding: 12px;
      margin-bottom: 8px;
      box-shadow: var(--sh0)
    }

    .det-item-thumb {
      width: 48px;
      height: 48px;
      border-radius: var(--r1);
      background: var(--creamd);
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center
    }

    .det-item-info {
      flex: 1
    }

    .det-item-name {
      font-size: 13px;
      font-weight: 700
    }

    .det-item-opts {
      font-size: 11px;
      color: var(--g4);
      margin-top: 2px
    }

    .det-item-price {
      font-size: 13px;
      font-weight: 800;
      color: var(--red);
      margin-top: 3px
    }

    .det-addr {
      display: flex;
      gap: 8px;
      align-items: flex-start;
      background: #fff;
      border-radius: var(--r2);
      padding: 12px;
      box-shadow: var(--sh0)
    }

    /* ─── EDIT MENU ─── */
    #edit-menu {
      background: var(--cream)
    }

    .toggle-wrap {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #fff;
      border-radius: var(--r2);
      padding: 14px;
      box-shadow: var(--sh0);
      margin-bottom: 10px
    }

    .toggle-lbl {
      font-size: 14px;
      font-weight: 600;
      color: var(--bk)
    }

    .toggle-sub {
      font-size: 11px;
      color: var(--g4);
      margin-top: 2px
    }

    .toggle {
      width: 44px;
      height: 24px;
      border-radius: 12px;
      background: var(--g2);
      border: none;
      cursor: pointer;
      position: relative;
      transition: background .2s;
      flex-shrink: 0
    }

    .toggle::after {
      content: '';
      position: absolute;
      top: 2px;
      left: 2px;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: #fff;
      transition: transform .2s;
      box-shadow: 0 1px 4px rgba(0, 0, 0, .2)
    }

    .toggle.on {
      background: var(--red)
    }

    .toggle.on::after {
      transform: translateX(20px)
    }

    .noteinp {
      width: 100%;
      padding: 10px 12px;
      border-radius: var(--r1);
      border: 2px solid var(--g2);
      background: #fff;
      font-family: 'Inter', sans-serif;
      font-size: 13px;
      resize: none;
      outline: none;
      margin-top: 4px
    }

    .noteinp:focus {
      border-color: var(--red)
    }

    .ccard {
      background: #fff;
      border-radius: var(--r2);
      padding: 14px;
      margin-bottom: 10px;
      box-shadow: var(--sh0)
    }

    .ccard-title {
      font-size: 11px;
      font-weight: 700;
      color: var(--g6);
      text-transform: uppercase;
      letter-spacing: .6px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 6px
    }

    .srow {
      display: flex;
      justify-content: space-between;
      margin-bottom: 7px;
      font-size: 13px;
      color: var(--g6)
    }

    .srow.tot {
      font-size: 15px;
      font-weight: 800;
      color: var(--bk);
      margin-top: 8px;
      padding-top: 8px;
      border-top: 2px dashed var(--g2)
    }

    .srow.tot span:last-child {
      color: var(--red)
    }

    /* ─── OVERLAY / BOTTOM SHEET ─── */
    .overlay {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, .5);
      z-index: 50;
      display: none;
      align-items: flex-end
    }

    .overlay.on {
      display: flex;
      animation: fIn .2s ease
    }

    @keyframes fIn {
      from {
        opacity: 0
      }

      to {
        opacity: 1
      }
    }

    .bsheet {
      background: #fff;
      border-radius: var(--r4) var(--r4) 0 0;
      width: 100%;
      padding: 18px 20px 36px;
      animation: sUp .3s cubic-bezier(.34, 1.56, .64, 1)
    }

    @keyframes sUp {
      from {
        transform: translateY(100%)
      }

      to {
        transform: translateY(0)
      }
    }

    .shandle {
      width: 40px;
      height: 4px;
      background: var(--g2);
      border-radius: 2px;
      margin: 0 auto 18px
    }

    .stitle {
      font-size: 17px;
      font-weight: 800;
      margin-bottom: 14px
    }

    .cdialog {
      background: #fff;
      border-radius: var(--r4) var(--r4) 0 0;
      width: 100%;
      padding: 28px 20px 36px;
      animation: sUp .3s cubic-bezier(.34, 1.56, .64, 1);
      text-align: center
    }

    .cd-icon {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px
    }

    .cd-icon.red {
      background: rgba(158, 9, 15, .1)
    }

    .cd-icon.green {
      background: rgba(39, 174, 96, .1)
    }

    .cd-title {
      font-size: 18px;
      font-weight: 800;
      margin-bottom: 8px
    }

    .cd-sub {
      font-size: 13px;
      color: var(--g6);
      line-height: 1.6;
      margin-bottom: 24px
    }

    .cd-btns {
      display: flex;
      gap: 10px
    }

    .cd-btns button {
      flex: 1
    }

    /* MISC */
    .notice {
      background: var(--creamd);
      border-radius: var(--r2);
      padding: 12px;
      display: flex;
      gap: 8px;
      align-items: flex-start;
      font-size: 12px;
      color: var(--g6);
      line-height: 1.5
    }

    .mt8 {
      margin-top: 8px
    }

    .mt12 {
      margin-top: 12px
    }

    .mt16 {
      margin-top: 16px
    }

    /* ─── SALES MANAGEMENT ─── */
    .sales-period-bar {
      display: flex;
      gap: 8px;
      padding: 14px 20px 0;
      flex-shrink: 0
    }

    .speriod {
      padding: 7px 16px;
      border-radius: 999px;
      border: 1.5px solid var(--g2);
      background: #fff;
      font-family: 'Inter', sans-serif;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      color: var(--g6);
      transition: all .2s;
      white-space: nowrap
    }

    .speriod.on {
      background: var(--yellow);
      border-color: var(--yellow);
      color: #7A5000;
      box-shadow: 0 3px 10px rgba(255, 178, 30, .35)
    }

    .sales-kpi-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      padding: 14px 20px 0
    }

    .kpi-card {
      background: #fff;
      border-radius: var(--r2);
      padding: 13px;
      box-shadow: var(--sh0);
      position: relative;
      overflow: hidden
    }

    .kpi-card::before {
      content: '';
      position: absolute;
      top: -12px;
      right: -12px;
      width: 56px;
      height: 56px;
      border-radius: 50%;
      opacity: .08
    }

    .kpi-card.k1::before {
      background: var(--red)
    }

    .kpi-card.k2::before {
      background: #27AE60
    }

    .kpi-card.k3::before {
      background: #1E76D2
    }

    .kpi-card.k4::before {
      background: #B8820A
    }

    .kpi-ic {
      width: 30px;
      height: 30px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 8px
    }

    .kpi-ic.k1 {
      background: rgba(158, 9, 15, .1);
      color: var(--red)
    }

    .kpi-ic.k2 {
      background: rgba(39, 174, 96, .1);
      color: #27AE60
    }

    .kpi-ic.k3 {
      background: rgba(30, 118, 210, .1);
      color: #1E76D2
    }

    .kpi-ic.k4 {
      background: rgba(184, 130, 10, .1);
      color: #B8820A
    }

    .kpi-val {
      font-size: 18px;
      font-weight: 900;
      color: var(--bk);
      line-height: 1
    }

    .kpi-lbl {
      font-size: 10px;
      color: var(--g4);
      font-weight: 600;
      margin-top: 3px;
      text-transform: uppercase;
      letter-spacing: .4px
    }

    .kpi-change {
      font-size: 10px;
      font-weight: 700;
      margin-top: 5px
    }

    .kpi-change.up {
      color: #27AE60
    }

    .kpi-change.dn {
      color: var(--red)
    }

    .chart-wrap {
      background: #fff;
      border-radius: 20px;
      margin: 12px 20px 0;
      padding: 16px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, .07)
    }

    .chart-title {
      font-size: 14px;
      font-weight: 800;
      color: var(--bk);
      margin-bottom: 14px;
      display: flex;
      justify-content: space-between;
      align-items: center
    }

    .chart-subtitle {
      font-size: 11px;
      color: var(--g4);
      font-weight: 500
    }

    .bar-chart {
      display: flex;
      align-items: flex-end;
      gap: 5px;
      height: 90px;
      padding: 0 2px
    }

    .bar-col {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
      cursor: pointer
    }

    .bar-fill {
      width: 100%;
      border-radius: 5px 5px 0 0;
      transition: all .3s;
      min-height: 4px
    }

    .bar-fill.active {
      background: var(--red) !important;
      box-shadow: 0 2px 8px rgba(158, 9, 15, .3)
    }

    .bar-lbl {
      font-size: 8px;
      color: var(--g4);
      font-weight: 600;
      white-space: nowrap
    }

    .bar-amt {
      font-size: 8px;
      color: var(--g6);
      font-weight: 700
    }

    .bestseller-list {
      background: #fff;
      border-radius: 20px;
      margin: 12px 20px 0;
      padding: 16px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, .07)
    }

    .bsitem {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 0;
      border-bottom: 1px solid var(--g2)
    }

    .bsitem:last-child {
      border-bottom: none
    }

    .bsrank {
      width: 22px;
      height: 22px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      font-weight: 900;
      flex-shrink: 0
    }

    .bsrank.r1 {
      background: var(--yellow);
      color: #7A5000
    }

    .bsrank.r2 {
      background: var(--g2);
      color: var(--g6)
    }

    .bsrank.r3 {
      background: rgba(184, 115, 51, .2);
      color: #A0522D
    }

    .bsrank.rn {
      background: var(--g1);
      color: var(--g4)
    }

    .bsname {
      flex: 1;
      font-size: 12px;
      font-weight: 700;
      color: var(--bk)
    }

    .bsqty {
      font-size: 11px;
      color: var(--g4);
      margin-top: 1px
    }

    .bsrev {
      font-size: 12px;
      font-weight: 800;
      color: var(--red)
    }

    .bs-bar {
      height: 4px;
      background: var(--g2);
      border-radius: 2px;
      margin-top: 4px;
      overflow: hidden
    }

    .bs-bar-fill {
      height: 100%;
      border-radius: 2px;
      background: linear-gradient(90deg, var(--red), var(--redl))
    }

    .txn-section {
      margin: 12px 20px 0
    }

    .txn-hdr {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px
    }

    .txn-title {
      font-size: 13px;
      font-weight: 700;
      color: var(--bk)
    }

    .txn-export {
      font-size: 12px;
      color: #fff;
      font-weight: 700;
      cursor: pointer;
      background: var(--red);
      padding: 6px 14px;
      border-radius: 50px;
      box-shadow: 0 3px 8px rgba(158, 9, 15, .3);
      transition: all .2s;
    }

    .txn-export:active {
      transform: scale(.95);
    }


    .txn-row {
      background: #fff;
      border-radius: var(--r2);
      padding: 11px 13px;
      margin-bottom: 8px;
      box-shadow: var(--sh0);
      cursor: pointer;
      transition: transform .15s
    }

    .txn-row:active {
      transform: scale(.97)
    }

    .txn-top {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 4px
    }

    .txn-id {
      font-size: 11px;
      color: var(--g4)
    }

    .txn-time {
      font-size: 10px;
      color: var(--g4)
    }

    .txn-cust {
      font-size: 13px;
      font-weight: 700;
      color: var(--bk)
    }

    .txn-items {
      font-size: 11px;
      color: var(--g6);
      margin-bottom: 5px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis
    }

    .txn-bot {
      display: flex;
      justify-content: space-between;
      align-items: center
    }

    .txn-pay {
      display: inline-flex;
      align-items: center;
      gap: 3px;
      font-size: 10px;
      font-weight: 600;
      padding: 2px 7px;
      border-radius: 50px
    }

    .txn-pay.cod {
      background: rgba(255, 178, 30, .15);
      color: #9A6A00
    }

    .txn-pay.tf {
      background: rgba(30, 118, 210, .1);
      color: #1E76D2
    }

    .txn-pay.qr {
      background: rgba(39, 174, 96, .1);
      color: #27AE60
    }

    .txn-total {
      font-size: 13px;
      font-weight: 900;
      color: var(--red)
    }

    .summary-box {
      background: var(--red);
      border-radius: 22px;
      margin: 12px 20px 0;
      padding: 18px 18px 16px;
      color: #fff
    }

    .sb-title {
      font-size: 12px;
      font-weight: 800;
      color: #fff;
      text-transform: uppercase;
      letter-spacing: .6px;
      margin-bottom: 14px;
      opacity: 1
    }

    .sb-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 9px;
      font-size: 13px
    }

    .sb-row:last-child {
      margin-bottom: 0;
      padding-top: 10px;
      border-top: 1px solid rgba(255, 255, 255, .25);
      font-size: 15px;
      font-weight: 800
    }

    .sb-row span:first-child {
      opacity: .9
    }

    .sb-row span:last-child {
      font-weight: 700
    }

    /* CONFIRMATION OVERLAY */
    .ovl-confirm {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.7);
      z-index: 2000;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
      backdrop-filter: blur(8px);
    }

    .ovl-confirm.show {
      display: flex;
    }

    .confirm-card {
      background: #fff;
      border-radius: 32px;
      width: 100%;
      max-width: 340px;
      padding: 36px 24px;
      text-align: center;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
      animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes pop {
      from {
        transform: scale(0.8);
        opacity: 0;
      }

      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    .c-icon-box {
      width: 76px;
      height: 76px;
      background: #FFF0F0;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 24px;
      color: var(--red);
    }

    .c-title {
      font-size: 20px;
      font-weight: 800;
      color: var(--bk);
      margin-bottom: 10px;
    }

    .c-text {
      font-size: 14px;
      color: var(--g6);
      line-height: 1.6;
      margin-bottom: 32px;
    }

    .c-btns {
      display: flex;
      gap: 12px;
    }

    .c-btn {
      flex: 1;
      padding: 14px;
      border-radius: 50px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      font-family: inherit;
      transition: all 0.2s;
    }

    .c-btn-no {
      background: var(--g1);
      color: var(--g6);
      border: none;
    }

    .c-btn-yes {
      background: var(--red);
      color: #fff;
      border: none;
      box-shadow: 0 4px 15px rgba(158, 9, 15, 0.25);
    }

    .c-btn:active {
      transform: scale(0.95);
    }
  </style>
</head>

<body>
  <div class="app">

  <!-- SVG ICONS -->
  <svg style="display:none">
    <defs>
      <symbol id="I-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
        stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6" />
      </symbol>
      <symbol id="I-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
        stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 18 15 12 9 6" />
      </symbol>
      <symbol id="I-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
        stroke-linecap="round">
        <line x1="12" y1="5" x2="12" y2="19" />
        <line x1="5" y1="12" x2="19" y2="12" />
      </symbol>
      <symbol id="I-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
        stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12" />
      </symbol>
      <symbol id="I-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
        <line x1="18" y1="6" x2="6" y2="18" />
        <line x1="6" y1="6" x2="18" y2="18" />
      </symbol>
      <symbol id="I-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z" />
        <circle cx="12" cy="10" r="3" />
      </symbol>
      <symbol id="I-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
        <polyline points="16 17 21 12 16 7" />
        <line x1="21" y1="12" x2="9" y2="12" />
      </symbol>
      <symbol id="I-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <rect x="3" y="11" width="18" height="11" rx="2" />
        <path d="M7 11V7a5 5 0 0110 0v4" />
      </symbol>
      <symbol id="I-mail" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
        <polyline points="22,6 12,13 2,6" />
      </symbol>
      <symbol id="I-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <line x1="18" y1="20" x2="18" y2="10" />
        <line x1="12" y1="20" x2="12" y2="4" />
        <line x1="6" y1="20" x2="6" y2="14" />
      </symbol>
      <symbol id="I-clip" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2" />
        <rect x="8" y="2" width="8" height="4" rx="1" />
      </symbol>
      <symbol id="I-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7" />
        <rect x="14" y="3" width="7" height="7" />
        <rect x="14" y="14" width="7" height="7" />
        <rect x="3" y="14" width="7" height="7" />
      </symbol>
      <symbol id="I-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9" />
        <path d="M13.73 21a2 2 0 01-3.46 0" />
      </symbol>
      <symbol id="I-star" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5"
        stroke-linecap="round" stroke-linejoin="round">
        <polygon
          points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
      </symbol>
      <symbol id="I-trending" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
        <polyline points="17 6 23 6 23 12" />
      </symbol>
      <symbol id="I-food" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.8"
        stroke-linecap="round" stroke-linejoin="round">
        <ellipse cx="16" cy="21" rx="11" ry="5" />
        <path d="M5 21c0-4 4.5-9 11-9s11 5 11 9" />
        <path d="M12 12c0-3 1.5-6 4-6s4 3 4 6" />
        <path d="M10 12c-1-2-1-4 0-5" />
        <path d="M22 12c1-2 1-4 0-5" />
      </symbol>
      <symbol id="I-img" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <rect x="3" y="3" width="18" height="18" rx="2" />
        <circle cx="8.5" cy="8.5" r="1.5" />
        <polyline points="21 15 16 10 5 21" />
      </symbol>
      <symbol id="I-check-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 11.08V12a10 10 0 11-5.93-9.14" />
        <polyline points="22 4 12 14.01 9 11.01" />
      </symbol>
      <symbol id="I-x-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10" />
        <line x1="15" y1="9" x2="9" y2="15" />
        <line x1="9" y1="9" x2="15" y2="15" />
      </symbol>
      <symbol id="I-truck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <rect x="1" y="3" width="15" height="13" />
        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
        <circle cx="5.5" cy="18.5" r="2.5" />
        <circle cx="18.5" cy="18.5" r="2.5" />
      </symbol>
      <symbol id="I-dollar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="1" x2="12" y2="23" />
        <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
      </symbol>
      <symbol id="I-receipt" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z" />
        <line x1="9" y1="7" x2="15" y2="7" />
        <line x1="9" y1="11" x2="15" y2="11" />
        <line x1="9" y1="15" x2="13" y2="15" />
      </symbol>
      <symbol id="I-wallet" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 12V7H5a2 2 0 010-4h14v4" />
        <path d="M3 7v13a2 2 0 002 2h16v-5" />
        <path d="M18 12a2 2 0 000 4h4v-4z" />
      </symbol>
      <symbol id="I-award" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <circle cx="12" cy="8" r="6" />
        <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11" />
      </symbol>
      <symbol id="I-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <polyline points="3 6 5 6 21 6" />
        <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
        <path d="M10 11v6" />
        <path d="M14 11v6" />
        <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" />
      </symbol>
    </defs>
  </svg>

  <!-- CONFIRMATION OVERLAY -->
  <div class="ovl-confirm" id="ovl-confirm">
    <div class="confirm-card">
      <div class="c-icon-box">
        <svg width="36" height="36">
          <use href="#I-logout" />
        </svg>
      </div>
      <div class="c-title" id="confirm-title">Konfirmasi</div>
      <div class="c-text" id="confirm-text">Apakah kamu yakin ingin melanjutkan?</div>
      <div class="c-btns">
        <button class="c-btn c-btn-no" onclick="closeConfirm()">Batal</button>
        <button class="c-btn c-btn-yes" id="confirm-yes-btn">Ya, Lanjutkan</button>
      </div>
    </div>
  </div>


  <!-- ═══ SELLER DASHBOARD ═══ -->
  <div class="screen on" id="seller">
    <div class="shdr">
      <div class="shdr-top">
        <div>
          <div class="sgreet">Dashboard Penjual</div>
          <div class="sname">Hayo Chicken</div>
        </div>
        <button class="ibtn" onclick="confirmLogout()"><svg width="18" height="18">

            <use href="#I-logout" />
          </svg></button>
      </div>
      <div class="sgrid">
        <div class="scard">
          <div class="sic"><svg width="18" height="18"><use href="#I-clip" /></svg></div>
          <div class="sval">{{ $todayOrderCount }}</div>
          <div class="slbl">Pesanan Hari Ini</div>
        </div>
        <div class="scard" style="background:rgba(255,255,255,0.15)">
          <div class="sic"><svg width="18" height="18"><use href="#I-chart" /></svg></div>
          <div class="sval">Rp{{ $totalRevenue >= 1000000 ? number_format($totalRevenue/1000000, 1) . 'jt' : number_format($totalRevenue/1000, 0) . 'k' }}</div>
          <div class="slbl">Pendapatan</div>
        </div>
        <div class="scard" style="background:rgba(255,255,255,0.12)">
          <div class="sic"><svg width="18" height="18"><use href="#I-bell" /></svg></div>
          <div class="sval">{{ $newOrderCount }}</div>
          <div class="slbl">Pesanan Baru</div>
        </div>
        <div class="scard" style="background:rgba(255,255,255,0.12)">
          <div class="sic"><svg width="18" height="18"><use href="#I-star" /></svg></div>
          <div class="sval">4.8</div>
          <div class="slbl">Rating Toko</div>
        </div>
      </div>
    </div><!-- .shdr -->
    <!-- Filter Status Pesanan (REQ-F-035) -->
    <div id="order-filter-bar" style="display:flex;gap:8px;padding:12px 16px 0;flex-shrink:0;background:var(--cream);overflow-x:auto;scrollbar-width:none">
      <button class="speriod on" onclick="setOrderFilter(this,'all')">Semua</button>
      <button class="speriod" onclick="setOrderFilter(this,'new')">Baru</button>
      <button class="speriod" onclick="setOrderFilter(this,'pending_verification')">Verif. QRIS</button>
      <button class="speriod" onclick="setOrderFilter(this,'processing')">Diproses</button>
      <button class="speriod" onclick="setOrderFilter(this,'delivering')">Dikirim</button>
      <button class="speriod" onclick="setOrderFilter(this,'done')">Selesai</button>
      <button class="speriod" onclick="setOrderFilter(this,'rejected')">Ditolak</button>
    </div>

    <div class="scroll" id="st-orders">
      <div style="padding:12px 16px 24px" id="seller-orders-list"></div>
    </div>
    <div class="scroll" id="st-menu" style="display:none">
      <div style="padding:12px 16px 24px" id="seller-menu-list"></div>
    </div>
    <!-- ═══ TAB PENJUALAN ═══ -->
    <div id="st-sales" style="display:none;flex-direction:column;flex:1;overflow:hidden;min-height:0">
      <div class="sales-period-bar" style="flex-shrink:0;background:var(--cream)">
        <button class="speriod on" onclick="setSalesPeriod(this,'today')">Hari Ini</button>
        <button class="speriod" onclick="setSalesPeriod(this,'week')">Minggu Ini</button>
        <button class="speriod" onclick="setSalesPeriod(this,'month')">Bulan Ini</button>
      </div>
      <div style="flex:1;overflow-y:auto;overflow-x:hidden;scrollbar-width:none;-webkit-overflow-scrolling:touch">
        <div class="sales-kpi-grid" id="sales-kpi"></div>
        <div class="chart-wrap">
          <div class="chart-title">Grafik Pendapatan <span class="chart-subtitle" id="chart-period-lbl">Per Jam (Hari
              Ini)</span></div>
          <div class="bar-chart" id="sales-bar-chart"></div>
        </div>
        <div class="summary-box" id="sales-summary-box"></div>
        <div class="bestseller-list">
          <div
            style="font-size:12px;font-weight:700;color:var(--bk);margin-bottom:10px;display:flex;align-items:center;gap:6px">
            <svg width="14" height="14" style="color:var(--yellow)">
              <use href="#I-award" />
            </svg> Menu Terlaris
          </div>
          <div id="bestseller-list-content"></div>
        </div>
        <div class="txn-section">
          <div class="txn-hdr">
            <div class="txn-title">Rincian Transaksi</div>
            <div class="txn-export" onclick="alert('Export ke CSV — akan terhubung backend')">⬇ Export</div>
          </div>
          <div id="txn-list"></div>
        </div>
        <!-- Panel Rekomendasi Stok / Smart Forecasting (REQ-F-043 s.d. REQ-F-046) -->
        <div id="bom-panel" style="margin:12px 20px 0;background:#fff;border-radius:20px;padding:16px;box-shadow:0 2px 12px rgba(0,0,0,.07)">
          <div style="font-size:12px;font-weight:800;color:var(--bk);text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px;display:flex;align-items:center;gap:6px">
            <svg width="14" height="14" style="color:var(--red)"><use href="#I-trending"/></svg>
            Rekomendasi Pengadaan Stok
          </div>
          <div id="bom-list"></div>
        </div>
        <div style="height:24px"></div>
      </div>
    </div>
    <button class="fab" id="fab-add" onclick="showOvl('ovl-add-menu')">
      <svg width="22" height="22">
        <use href="#I-plus" />
      </svg>
    </button>
    <nav class="seller-nav bnav">
      <button class="ni on"
        onclick="sellerTabNav('st-orders'); this.parentElement.querySelectorAll('.ni').forEach(n=>n.classList.remove('on')); this.classList.add('on')">
        <div class="niwrap"><svg width="20" height="20" class="nsvg">
            <use href="#I-clip" />
          </svg></div><span class="nlbl">Pesanan</span>
      </button>

      <button class="ni"
        onclick="sellerTabNav('st-menu'); this.parentElement.querySelectorAll('.ni').forEach(n=>n.classList.remove('on')); this.classList.add('on')">
        <div class="niwrap"><svg width="20" height="20" class="nsvg">
            <use href="#I-grid" />
          </svg></div><span class="nlbl">Menu</span>
      </button>
      <button class="ni"
        onclick="sellerTabNav('st-sales'); this.parentElement.querySelectorAll('.ni').forEach(n=>n.classList.remove('on')); this.classList.add('on')">
        <div class="niwrap"><svg width="20" height="20" class="nsvg">
            <use href="#I-trending" />
          </svg></div><span class="nlbl">Penjualan</span>
      </button>
    </nav>
  </div>

  <!-- ═══ SELLER ORDER DETAIL ═══ -->
  <div class="screen" id="seller-order-detail">
    <div class="order-det-header">
      <button class="back-circ" onclick="go('seller')"><svg width="18" height="18">
          <use href="#I-left" />
        </svg></button>
      <div class="order-det-id" id="sod-id">#HC-2024-0042</div>
      <div class="order-det-name" id="sod-name">Andi Mahasiswa</div>
      <div class="order-det-badge" id="sod-badge"></div>
    </div>
    <div class="scroll">
      <div style="padding:14px 20px">
        <div
          style="font-size:12px;font-weight:700;color:var(--g6);text-transform:uppercase;letter-spacing:.6px;margin-bottom:10px">
          Isi Pesanan</div>
        <div id="sod-items"></div>
        <div
          style="font-size:12px;font-weight:700;color:var(--g6);text-transform:uppercase;letter-spacing:.6px;margin:14px 0 10px">
          Info Pengiriman</div>
        <div class="det-addr">
          <svg width="16" height="16" style="color:var(--red);flex-shrink:0;margin-top:2px">
            <use href="#I-pin" />
          </svg>
          <div>
            <div style="font-size:13px;font-weight:700;margin-bottom:3px">Alamat</div>
            <div style="font-size:13px;color:var(--g6)" id="sod-addr">-</div>
          </div>
        </div>
        <div style="margin-top:10px;background:#fff;border-radius:var(--r2);padding:12px;box-shadow:var(--sh0)">
          <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:13px;color:var(--g6)">
            <span>Total Pembayaran</span><span style="font-weight:800;color:var(--red)" id="sod-total">-</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--g6)"><span>Metode
              Bayar</span><span style="font-weight:600" id="sod-pay">COD</span></div>
        </div>
        <div id="sod-action-area" style="margin-top:16px"></div>
      </div>
      <div style="height:20px"></div>
    </div>
  </div>

  <!-- ═══ EDIT MENU ═══ -->
  <div class="screen" id="edit-menu">
    <div class="phdr">
      <div class="phdr-row"><button class="pbtn" onclick="go('seller')"><svg width="18" height="18">
            <use href="#I-left" />
          </svg></button>
        <div class="ptitle" id="edit-menu-title">Edit Menu</div>
      </div>
    </div>
    <div class="scroll">
      <div style="padding:14px 20px">
        <div
          style="background:#fff;border-radius:var(--r2);padding:16px;margin-bottom:10px;box-shadow:var(--sh0);text-align:center;cursor:pointer;border:2px dashed var(--g2)"
          onclick="alert('Upload foto — akan terhubung ke backend')">
          <svg width="32" height="32" style="color:var(--g4);margin:0 auto 8px;display:block">
            <use href="#I-img" />
          </svg>
          <div style="font-size:13px;font-weight:600;color:var(--g6)">Tap untuk upload foto menu</div>
          <div style="font-size:11px;color:var(--g4);margin-top:3px">JPG, PNG max 5MB</div>
        </div>
        <div class="ccard">
          <div class="fg"><label class="flbl">Nama Menu</label><input class="finput np" type="text" id="em-name"
              placeholder="Nama menu..."></div>
          <div class="fg"><label class="flbl">Deskripsi</label><textarea class="noteinp" style="margin-top:0" rows="2"
              id="em-desc" placeholder="Deskripsi singkat..."></textarea></div>
          <div class="fg"><label class="flbl">Harga (Rp)</label><input class="finput np" type="number" id="em-price"
              placeholder="12000"></div>
          <div class="fg" style="margin-bottom:0"><label class="flbl">Kategori</label>
            <select class="finput np" id="em-cat">
              <option value="ayam">Ayam</option>
              <option value="cemilan">Cemilan</option>
              <option value="minuman">Minuman</option>
              <option value="paket">Paket</option>
            </select>
          </div>
        </div>
        <div class="toggle-wrap">
          <div>
            <div class="toggle-lbl">Stok Tersedia</div>
            <div class="toggle-sub">Nonaktifkan jika stok habis</div>
          </div>
          <button class="toggle on" id="em-toggle" onclick="this.classList.toggle('on')"></button>
        </div>
        <div id="em-id" style="display:none"></div>
        <button class="btn-main" onclick="saveMenu()">Simpan Menu</button>
        <button class="btn-main outline mt8" id="em-del"
          onclick="confirmDeleteMenu(parseInt(document.getElementById('em-id').textContent))" style="display:none">Hapus
          Menu</button>

      </div>
      <div style="height:16px"></div>
    </div>
  </div>

  <!-- ═══════ OVERLAYS ═══════ -->

  <!-- Add Menu -->
  <div class="overlay" id="ovl-add-menu" onclick="hideOvl('ovl-add-menu')">
    <div class="bsheet" onclick="event.stopPropagation()">
      <div class="shandle"></div>
      <div class="stitle">Tambah Menu Baru</div>
      <div class="fg"><label class="flbl">Nama Menu</label><input class="finput np" type="text" id="am-name"
          placeholder="Nama menu..."></div>
      <div class="fg"><label class="flbl">Harga (Rp)</label><input class="finput np" type="number" id="am-price"
          placeholder="12000"></div>
      <div class="fg"><label class="flbl">Kategori</label><select class="finput np" id="am-cat">
          <option value="ayam">Ayam</option>
          <option value="cemilan">Cemilan</option>
          <option value="minuman">Minuman</option>
          <option value="paket">Paket</option>
        </select></div>
      <button class="btn-main" onclick="quickAddMenu()">Tambah Menu</button>
    </div>
  </div>

  <!-- Confirm Accept -->
  <div class="overlay" id="ovl-confirm-accept" onclick="hideOvl('ovl-confirm-accept')">
    <div class="cdialog" onclick="event.stopPropagation()">
      <div class="cd-icon green"><svg width="36" height="36" style="color:#27AE60">
          <use href="#I-check-circle" />
        </svg></div>
      <div class="cd-title">Terima Pesanan?</div>
      <div class="cd-sub">Pesanan akan segera diproses dan kamu harus segera menyiapkan makanannya.</div>
      <div class="cd-btns">
        <button class="btn-main outline" onclick="hideOvl('ovl-confirm-accept')">Batal</button>
        <button class="btn-main" onclick="confirmAccept()">Ya, Terima</button>
      </div>
    </div>
  </div>

  <!-- Confirm Reject -->
  <div class="overlay" id="ovl-confirm-reject" onclick="hideOvl('ovl-confirm-reject')">
    <div class="cdialog" onclick="event.stopPropagation()">
      <div class="cd-icon red"><svg width="36" height="36" style="color:var(--red)">
          <use href="#I-x-circle" />
        </svg></div>
      <div class="cd-title">Tolak Pesanan?</div>
      <div class="cd-sub">Pesanan akan dibatalkan dan pembeli akan mendapat notifikasi bahwa pesanan ditolak.</div>
      <div class="cd-btns">
        <button class="btn-main outline" onclick="hideOvl('ovl-confirm-reject')">Batal</button>
        <button class="btn-main" style="background:var(--g8);box-shadow:none" onclick="confirmReject()">Ya,
          Tolak</button>
      </div>
    </div>
  </div>

  <!-- Confirm Send -->
  <div class="overlay" id="ovl-confirm-send" onclick="hideOvl('ovl-confirm-send')">
    <div class="cdialog" onclick="event.stopPropagation()">
      <div class="cd-icon green"><svg width="36" height="36" style="color:#27AE60">
          <use href="#I-truck" />
        </svg></div>
      <div class="cd-title">Kirim Pesanan?</div>
      <div class="cd-sub">Pesanan akan ditandai sebagai "Dalam Pengiriman". Pastikan kamu sudah berangkat!</div>
      <div class="cd-btns">
        <button class="btn-main outline" onclick="hideOvl('ovl-confirm-send')">Batal</button>
        <button class="btn-main" onclick="confirmSend()">Ya, Kirim Sekarang</button>
      </div>
    </div>
  </div>

  <!-- Confirm Done -->
  <div class="overlay" id="ovl-confirm-done" onclick="hideOvl('ovl-confirm-done')">
    <div class="cdialog" onclick="event.stopPropagation()">
      <div class="cd-icon green"><svg width="36" height="36" style="color:#27AE60">
          <use href="#I-check-circle" />
        </svg></div>
      <div class="cd-title">Tandai Selesai?</div>
      <div class="cd-sub">Konfirmasi bahwa pesanan sudah diterima pembeli dengan baik.</div>
      <div class="cd-btns">
        <button class="btn-main outline" onclick="hideOvl('ovl-confirm-done')">Batal</button>
        <button class="btn-main" onclick="confirmDone()">Selesai</button>
      </div>
    </div>
  </div>

  <!-- Verify QRIS -->
  <div class="overlay" id="ovl-verify-qris" onclick="hideOvl('ovl-verify-qris')">
    <div class="cdialog" onclick="event.stopPropagation()">
      <div class="cd-icon green"><svg width="36" height="36" style="color:#27AE60">
          <use href="#I-dollar" />
        </svg></div>
      <div class="cd-title">Verifikasi QRIS</div>
      <div class="cd-sub">Silakan periksa bukti pembayaran berikut:</div>
      <div
        style="background:#f0f0f0; border-radius:12px; height:200px; margin-bottom:20px; display:flex; align-items:center; justify-content:center; color:#888;">
        <div style="text-align:center">
          <svg width="48" height="48" style="margin-bottom:8px; opacity:0.5">
            <use href="#I-img" />
          </svg>
          <div>[Bukti Transfer QRIS]</div>
        </div>
      </div>
      <div class="cd-btns">
        <button class="btn-main outline" onclick="hideOvl('ovl-verify-qris'); confirmReject();">Tolak</button>
        <button class="btn-main" onclick="hideOvl('ovl-verify-qris'); confirmAccept();">Verifikasi & Terima</button>
      </div>
    </div>
  </div>


  </div><!-- .app -->

  <script>
    // Sync server-side user data to localStorage for the frontend
    @if(session('userRole'))
        localStorage.setItem('userRole', '{{ session('userRole') }}');
    @endif
    @if(session('userName'))
        localStorage.setItem('userName', '{{ session('userName') }}');
    @endif
    @if(session('userEmail'))
        localStorage.setItem('userEmail', '{{ session('userEmail') }}');
    @endif

    // Guard: hanya seller yang boleh masuk
    (function () {
      var role = localStorage.getItem('userRole');
      if (role !== 'seller') {
        window.location.href = '{{ route('login') }}';
      }
    })();

    // ═══════════════════════════════════════════════════
    // DATA STORE
    // ═══════════════════════════════════════════════════
    const MENUS = @json($allProducts);
    let orders = @json($recentOrders).map(o => ({
        id: o.order_number,
        realId: o.id,
        custName: o.user ? o.user.name : 'Pelanggan',
        items: (o.order_items || []).map(i => ({ menuId: null, qty: i.quantity, name: i.name_snapshot })),
        addr: o.delivery_address,
        pay: o.payment_method,
        status: o.status.toLowerCase(),
        time: new Date(o.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}),
        total: o.total_amount
    }));
    const forecastingData = @json($forecasting);

    let pendingActionOid = null;

    // ═══════════════════════════════════════════════════
    // NAVIGATION
    // ═══════════════════════════════════════════════════
    function go(id) {
      document.querySelectorAll('.screen').forEach(s => { s.classList.remove('on'); s.style.display = ''; });
      const t = document.getElementById(id);
      if (t) { t.style.display = 'flex'; t.classList.add('on'); }
      if (id === 'seller') renderSeller();
      document.getElementById('fab-add') && (document.getElementById('fab-add').classList.remove('show'));
    }

    // ═══════════════════════════════════════════════════
    // AUTH
    // ═══════════════════════════════════════════════════

    // ═══════════════════════════════════════════════════
    // SELLER DASHBOARD
    // ═══════════════════════════════════════════════════
    function renderSeller() {
      // These elements were removed in the new 4-card header layout
      // document.getElementById('s-stat3').textContent = pending;
      // document.getElementById('s-stat1').textContent = orders.length;
      
      renderSellerOrders();
      renderSellerMenu();
    }

    const orderStatusCfg = {
      new: { label: 'Pesanan Baru', badge: 'bnew' },
      pending_verification: { label: 'Verifikasi QRIS', badge: 'bship' },
      processing: { label: 'Diproses', badge: 'bproc' },
      delivering: { label: 'Dikirim', badge: 'bship' },
      done: { label: 'Selesai', badge: 'bdone' },
      rejected: { label: 'Ditolak', badge: 'bcancel' },
    };

    let currentOrderFilter = 'all';

    function setOrderFilter(btn, filter) {
      document.querySelectorAll('#order-filter-bar .speriod').forEach(b => b.classList.remove('on'));
      btn.classList.add('on');
      currentOrderFilter = filter;
      renderSellerOrders();
    }

    function renderSellerOrders() {
      const list = document.getElementById('seller-orders-list');
      const filtered = currentOrderFilter === 'all' ? orders : orders.filter(o => o.status === currentOrderFilter);
      if (filtered.length === 0) {
        list.innerHTML = '<div style="text-align:center;padding:40px 20px;color:var(--g4);font-size:13px;font-weight:600;">Tidak ada pesanan</div>';
        return;
      }
      list.innerHTML = filtered.map(o => {
        const cfg = orderStatusCfg[o.status] || { label: o.status, badge: 'bcancel' };
        const itemsStr = o.items.map(i => i.name ? `${i.name} x${i.qty}` : '').filter(Boolean).join(' · ');
        let actions = '';
        if (o.status === 'new') actions = `<div style="display:flex;gap:6px"><button class="bsm o" onclick="openReject(event,'${o.id}')">Tolak</button><button class="bsm r" onclick="openAccept(event,'${o.id}')">Terima</button></div>`;
        else if (o.status === 'pending_verification') actions = `<div style="display:flex;gap:6px"><button class="bsm o" onclick="openReject(event,'${o.id}')">Tolak</button><button class="bsm r" onclick="openVerify(event,'${o.id}')">Verifikasi QRIS</button></div>`;
        else if (o.status === 'processing') actions = `<button class="bsm g" onclick="openSend(event,'${o.id}')">Kirim Sekarang</button>`;
        else if (o.status === 'delivering') actions = `<button class="bsm g" onclick="openDone(event,'${o.id}')">Tandai Selesai</button>`;
        else if (o.status === 'done') actions = `<span class="badge bdone">Selesai</span>`;
        else if (o.status === 'rejected') actions = `<span class="badge bcancel">Ditolak</span>`;
        return `<div class="ocard" onclick="openSellerOrder('${o.id}')">
      <div class="ocard-top"><div class="oid">#${o.id} · ${o.time}</div><span class="badge ${cfg.badge}">${cfg.label}</span></div>
      <div class="ocust">${o.custName}</div>
      <div class="oitems">${itemsStr}</div>
      <div class="oaddr"><svg width="12" height="12" style="color:var(--red);flex-shrink:0;margin-top:1px"><use href="#I-pin"/></svg><span>${o.addr}</span></div>
      <div class="ofoot"><span class="ototl">Rp${o.total.toLocaleString('id-ID')}</span>${actions}</div>
    </div>`;
      }).join('');
    }

    function renderSellerMenu() {
      const list = document.getElementById('seller-menu-list');
      list.innerHTML = MENUS.map(m => `
    <div class="smcard">
      <div class="smthumb"><svg width="26" height="26" style="color:var(--red)"><use href="#I-food"/></svg></div>
      <div class="sminfo">
        <div class="smname">${m.name}</div>
        <div class="smprice">Rp${(m.base_price || m.price || 0).toLocaleString('id-ID')}</div>
        <div class="smstock ${m.is_available ? '' : 'out'}">${m.is_available ? 'Tersedia' : 'Stok Habis'}</div>
      </div>
      <button class="bsm o" onclick="openEditMenu(${m.id})">Edit</button>
    </div>`).join('');
    }

    function selTab(btn, tid) {
      document.querySelectorAll('.stab').forEach(t => t.classList.remove('on')); btn.classList.add('on');
      document.getElementById('st-orders').style.display = 'none';
      document.getElementById('st-menu').style.display = 'none';
      document.getElementById('st-sales').style.display = 'none';
      if (tid === 'st-sales') {
        document.getElementById('st-sales').style.display = 'flex';
        renderSalesTab();
      } else {
        document.getElementById(tid).style.display = '';
      }
      document.getElementById('fab-add').classList.toggle('show', tid === 'st-menu');
    }
    function sellerTabNav(tid) {
      if (tid === 'st-orders') {
        document.getElementById('st-orders').style.display = '';
        document.getElementById('st-menu').style.display = 'none';
        document.getElementById('st-sales').style.display = 'none';
        document.getElementById('order-filter-bar').style.display = 'flex';
        document.getElementById('fab-add').classList.remove('show');
      } else if (tid === 'st-menu') {
        document.getElementById('st-orders').style.display = 'none';
        document.getElementById('st-menu').style.display = '';
        document.getElementById('st-sales').style.display = 'none';
        document.getElementById('order-filter-bar').style.display = 'none';
        document.getElementById('fab-add').classList.add('show');
      } else if (tid === 'st-sales') {
        document.getElementById('st-orders').style.display = 'none';
        document.getElementById('st-menu').style.display = 'none';
        document.getElementById('st-sales').style.display = 'flex';
        document.getElementById('order-filter-bar').style.display = 'none';
        document.getElementById('fab-add').classList.remove('show');
        renderSalesTab();
      }
    }


    // ═══ SELLER ORDER DETAIL ═══
    function openSellerOrder(id) {
      const o = orders.find(x => x.id === id); if (!o) return;
      document.getElementById('sod-id').textContent = '#' + o.id;
      document.getElementById('sod-name').textContent = o.custName;
      const cfg = orderStatusCfg[o.status] || { label: o.status, badge: 'bcancel' };
      document.getElementById('sod-badge').innerHTML = `<span class="badge ${cfg.badge}">${cfg.label}</span>`;
      document.getElementById('sod-items').innerHTML = o.items.map(i => {
        const m = MENUS.find(x => x.id === i.menuId); if (!m) return '';
        const sub = (m.price + i.opts.reduce((s, oo) => s + oo.price, 0)) * i.qty;
        return `<div class="det-item-row">
      <div class="det-item-thumb"><svg width="24" height="24" style="color:var(--red)"><use href="#I-food"/></svg></div>
      <div class="det-item-info">
        <div class="det-item-name">${m.name} x${i.qty}</div>
        ${i.opts.length ? `<div class="det-item-opts">+${i.opts.map(oo => oo.name).join(', ')}</div>` : ''}
        ${i.note ? `<div class="det-item-opts" style="font-style:italic">"${i.note}"</div>` : ''}
        <div class="det-item-price">Rp${sub.toLocaleString('id-ID')}</div>
      </div>
    </div>`;
      }).join('');
      document.getElementById('sod-addr').textContent = o.addr;
      document.getElementById('sod-total').textContent = 'Rp' + o.total.toLocaleString('id-ID');
      document.getElementById('sod-pay').textContent = o.pay;
      const area = document.getElementById('sod-action-area');
      if (o.status === 'new') area.innerHTML = `<div style="display:flex;gap:10px"><button class="btn-main outline" onclick="openReject(event,'${o.id}')">Tolak Pesanan</button><button class="btn-main" onclick="openAccept(event,'${o.id}')">Terima Pesanan</button></div>`;
      else if (o.status === 'pending_verification') area.innerHTML = `<div style="margin-bottom:8px;padding:8px 10px;background:rgba(30,118,210,0.08);border-radius:10px;font-size:12px;color:#1E76D2;font-weight:600;">⏳ Menunggu verifikasi bukti transfer QRIS</div><div style="display:flex;gap:10px"><button class="btn-main outline" onclick="openReject(event,'${o.id}')">Tolak</button><button class="btn-main" onclick="openVerify(event,'${o.id}')">Verifikasi & Proses</button></div>`;
      else if (o.status === 'processing') area.innerHTML = `<button class="btn-main" onclick="openSend(event,'${o.id}')">Kirim Sekarang</button>`;
      else if (o.status === 'delivering') area.innerHTML = `<button class="btn-main" onclick="openDone(event,'${o.id}')">Tandai Selesai</button>`;
      else area.innerHTML = `<div class="notice"><svg width="16" height="16" style="flex-shrink:0;margin-top:1px"><use href="#I-check-circle"/></svg>Pesanan ini sudah ${cfg.label.toLowerCase()}.</div>`;
      go('seller-order-detail');
    }

    // ═══ ORDER ACTIONS ═══
    function openAccept(e, id) { e && e.stopPropagation(); pendingActionOid = id; showOvl('ovl-confirm-accept'); }
    function openReject(e, id) { e && e.stopPropagation(); pendingActionOid = id; showOvl('ovl-confirm-reject'); }
    function openSend(e, id) { e && e.stopPropagation(); pendingActionOid = id; showOvl('ovl-confirm-send'); }
    function openDone(e, id) { e && e.stopPropagation(); pendingActionOid = id; showOvl('ovl-confirm-done'); }
    function openVerify(e, id) { e && e.stopPropagation(); pendingActionOid = id; showOvl('ovl-verify-qris'); }


    async function confirmAccept() {
      await updateOrderStatus(pendingActionOid, 'PROCESSING');
      hideOvl('ovl-confirm-accept'); 
    }
    async function confirmReject() {
      const reason = prompt("Alasan penolakan:", "Stok habis");
      if (!reason) return;
      await updateOrderStatus(pendingActionOid, 'REJECTED', reason);
      hideOvl('ovl-confirm-reject');
    }
    async function confirmSend() {
      await updateOrderStatus(pendingActionOid, 'DELIVERING');
      hideOvl('ovl-confirm-send');
    }
    async function confirmDone() {
      await updateOrderStatus(pendingActionOid, 'DONE');
      hideOvl('ovl-confirm-done');
    }

    async function updateOrderStatus(orderNum, status, note = '') {
      const order = orders.find(x => x.id === orderNum);
      if (!order) return;

      try {
        const response = await fetch(`/orders/${order.realId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status, note })
        });

        if (response.ok) {
            alert(`Status pesanan ${orderNum} diupdate ke ${status}`);
            location.reload();
        } else {
            const res = await response.json();
            alert(res.message || "Gagal mengupdate status.");
        }
      } catch (err) {
        console.error(err);
        alert("Terjadi kesalahan koneksi.");
      }
    }

    function refreshOrderViews() {
      renderSellerOrders();
    }

    // ═══ EDIT MENU ═══
    function openEditMenu(id) {
      const m = MENUS.find(x => x.id === id);
      document.getElementById('edit-menu-title').textContent = 'Edit Menu';
      document.getElementById('em-name').value = m.name;
      document.getElementById('em-desc').value = m.desc;
      document.getElementById('em-price').value = m.price;
      document.getElementById('em-cat').value = m.cat;
      document.getElementById('em-id').textContent = id;
      const tog = document.getElementById('em-toggle');
      tog.className = 'toggle' + (m.stock ? ' on' : '');
      document.getElementById('em-del').style.display = '';
      go('edit-menu');
    }
    function saveMenu() {
      const id = parseInt(document.getElementById('em-id').textContent);
      const name = document.getElementById('em-name').value.trim();
      const desc = document.getElementById('em-desc').value.trim();
      const price = parseInt(document.getElementById('em-price').value) || 0;
      const cat = document.getElementById('em-cat').value;
      const stock = document.getElementById('em-toggle').classList.contains('on');
      if (!name || !price) { alert('Nama dan harga wajib diisi!'); return; }
      if (id) {
        const idx = MENUS.findIndex(x => x.id === id);
        if (idx >= 0) { MENUS[idx] = { ...MENUS[idx], name, desc, price, cat, stock }; }
      } else {
        MENUS.push({ id: Date.now(), name, desc, price, cat, stock, extras: [] });
      }
      go('seller');
    }

    // ═══ ADD MENU (QUICK) ═══
    function quickAddMenu() {
      const name = document.getElementById('am-name').value.trim();
      const price = parseInt(document.getElementById('am-price').value) || 0;
      const cat = document.getElementById('am-cat').value;
      if (!name || !price) { alert('Nama dan harga wajib diisi!'); return; }
      MENUS.push({ id: Date.now(), name, desc: 'Menu baru', price, cat, stock: true, extras: [] });
      hideOvl('ovl-add-menu');
      renderSellerMenu();
    }

    // ═══ OVERLAYS ═══
    function showOvl(id) { document.getElementById(id).classList.add('on'); }
    function hideOvl(id) { document.getElementById(id).classList.remove('on'); }

    // ═══════════════════════════════════════════════════
    // SALES MANAGEMENT
    // ═══════════════════════════════════════════════════
    let currentSalesPeriod = 'today';

    const salesHistory = [
      { id: 'HC-0042', custName: 'Andi Mahasiswa', items: [{ menuId: 1, qty: 1 }, { menuId: 4, qty: 1 }], pay: 'COD', status: 'delivering', time: '12:34', hour: 12, day: 0, total: 17000 },
      { id: 'HC-0041', custName: 'Sari Putri', items: [{ menuId: 2, qty: 2 }, { menuId: 3, qty: 1 }], pay: 'COD', status: 'done', time: '12:20', hour: 12, day: 0, total: 44000 },
      { id: 'HC-0040', custName: 'Budi Santoso', items: [{ menuId: 5, qty: 2 }], pay: 'QRIS', status: 'done', time: '12:15', hour: 12, day: 0, total: 26000 },
      { id: 'HC-0039', custName: 'Maya Lestari', items: [{ menuId: 6, qty: 1 }], pay: 'COD', status: 'done', time: '11:50', hour: 11, day: 0, total: 30000 },
      { id: 'HC-0038', custName: 'Rizky Pratama', items: [{ menuId: 1, qty: 1 }, { menuId: 7, qty: 1 }], pay: 'COD', status: 'done', time: '11:30', hour: 11, day: 0, total: 16000 },
      { id: 'HC-0037', custName: 'Dewi Anggraini', items: [{ menuId: 2, qty: 1 }, { menuId: 8, qty: 2 }], pay: 'QRIS', status: 'done', time: '10:45', hour: 10, day: 0, total: 32000 },
      { id: 'HC-0036', custName: 'Fajar Nugroho', items: [{ menuId: 1, qty: 3 }], pay: 'COD', status: 'done', time: '10:10', hour: 10, day: 0, total: 36000 },
      { id: 'HC-0035', custName: 'Ayu Wulandari', items: [{ menuId: 4, qty: 2 }, { menuId: 7, qty: 1 }], pay: 'QRIS', status: 'done', time: '09:30', hour: 9, day: 0, total: 14000 },
      { id: 'HC-0034', custName: 'Hendra Wijaya', items: [{ menuId: 5, qty: 1 }, { menuId: 3, qty: 1 }], pay: 'COD', status: 'done', time: '09:00', hour: 9, day: 0, total: 21000 },
      { id: 'HC-0033', custName: 'Linda Kusuma', items: [{ menuId: 2, qty: 1 }], pay: 'COD', status: 'done', time: '13:00', hour: 13, day: 1, total: 18000 },
      { id: 'HC-0032', custName: 'Reza Firmansyah', items: [{ menuId: 6, qty: 2 }], pay: 'QRIS', status: 'done', time: '12:00', hour: 12, day: 1, total: 60000 },
      { id: 'HC-0031', custName: 'Siti Rahayu', items: [{ menuId: 1, qty: 2 }, { menuId: 4, qty: 1 }], pay: 'COD', status: 'done', time: '11:00', hour: 11, day: 1, total: 29000 },
      { id: 'HC-0030', custName: 'Bagas Pratama', items: [{ menuId: 8, qty: 3 }], pay: 'COD', status: 'done', time: '10:00', hour: 10, day: 1, total: 21000 },
      { id: 'HC-0029', custName: 'Nadia Pertiwi', items: [{ menuId: 5, qty: 1 }], pay: 'QRIS', status: 'done', time: '09:00', hour: 9, day: 1, total: 13000 },
      { id: 'HC-0028', custName: 'Irfan Hakim', items: [{ menuId: 2, qty: 3 }], pay: 'COD', status: 'done', time: '12:30', hour: 12, day: 2, total: 54000 },
      { id: 'HC-0027', custName: 'Yuli Astuti', items: [{ menuId: 1, qty: 2 }, { menuId: 7, qty: 2 }], pay: 'COD', status: 'done', time: '11:30', hour: 11, day: 2, total: 32000 },
      { id: 'HC-0026', custName: 'Doni Setiawan', items: [{ menuId: 3, qty: 2 }, { menuId: 4, qty: 2 }], pay: 'QRIS', status: 'done', time: '10:00', hour: 10, day: 2, total: 26000 },
      { id: 'HC-0025', custName: 'Rika Handayani', items: [{ menuId: 6, qty: 1 }, { menuId: 8, qty: 2 }], pay: 'COD', status: 'done', time: '13:00', hour: 13, day: 3, total: 44000 },
      { id: 'HC-0024', custName: 'Tono Wibowo', items: [{ menuId: 1, qty: 4 }], pay: 'COD', status: 'done', time: '12:00', hour: 12, day: 3, total: 48000 },
      { id: 'HC-0023', custName: 'Vina Permata', items: [{ menuId: 5, qty: 2 }, { menuId: 4, qty: 1 }], pay: 'QRIS', status: 'done', time: '10:30', hour: 10, day: 3, total: 31000 },
      { id: 'HC-0022', custName: 'Aldo Mahendra', items: [{ menuId: 2, qty: 2 }, { menuId: 3, qty: 1 }], pay: 'COD', status: 'done', time: '12:00', hour: 12, day: 4, total: 44000 },
      { id: 'HC-0021', custName: 'Putri Maulida', items: [{ menuId: 7, qty: 2 }, { menuId: 8, qty: 1 }], pay: 'QRIS', status: 'done', time: '11:00', hour: 11, day: 4, total: 15000 },
      { id: 'HC-0020', custName: 'Eko Prasetyo', items: [{ menuId: 1, qty: 1 }, { menuId: 2, qty: 1 }], pay: 'COD', status: 'done', time: '12:00', hour: 12, day: 5, total: 30000 },
      { id: 'HC-0019', custName: 'Wati Ningrum', items: [{ menuId: 6, qty: 1 }], pay: 'QRIS', status: 'done', time: '10:00', hour: 10, day: 5, total: 30000 },
      { id: 'HC-0018', custName: 'Joko Santoso', items: [{ menuId: 5, qty: 3 }], pay: 'COD', status: 'done', time: '13:00', hour: 13, day: 6, total: 39000 },
      { id: 'HC-0017', custName: 'Mega Puspita', items: [{ menuId: 3, qty: 2 }, { menuId: 4, qty: 3 }], pay: 'QRIS', status: 'done', time: '11:00', hour: 11, day: 6, total: 31000 },

    ];

    function getSalesData(period) {
      if (period === 'today') return salesHistory.filter(o => o.day === 0 && o.status === 'done');
      else if (period === 'week') return salesHistory.filter(o => o.day <= 6 && o.status === 'done');
      else return salesHistory.filter(o => o.status === 'done');
    }

    function setSalesPeriod(btn, period) {
      document.querySelectorAll('.speriod').forEach(b => b.classList.remove('on'));
      btn.classList.add('on');
      currentSalesPeriod = period;
      renderSalesTab();
    }

    function renderSalesTab() {
      const data = getSalesData(currentSalesPeriod);
      const totalRev = data.reduce((s, o) => s + o.total, 0);
      const totalOrders = data.length;
      const avgOrder = totalOrders ? Math.round(totalRev / totalOrders) : 0;
      const menuQty = {};
      data.forEach(o => o.items.forEach(i => { menuQty[i.menuId] = (menuQty[i.menuId] || 0) + i.qty; }));
      const prevRev = currentSalesPeriod === 'today' ? 214000 : currentSalesPeriod === 'week' ? 520000 : 1200000;
      const revChange = prevRev ? Math.round((totalRev - prevRev) / prevRev * 100) : 0;
      const res = { COD: 0, QRIS: 0 };
      data.forEach(o => {
        if (o.pay.includes('COD')) res.COD += o.total;
        else if (o.pay.includes('QRIS')) res.QRIS += o.total;
      });
      const topPay = Object.entries(res).sort((a, b) => b[1] - a[1])[0];


      document.getElementById('sales-kpi').innerHTML = `
    <div class="kpi-card k1">
      <div class="kpi-ic k1"><svg width="15" height="15"><use href="#I-dollar"/></svg></div>
      <div class="kpi-val">Rp${totalRev >= 1000000 ? (totalRev / 1000000).toFixed(1) + 'jt' : Math.round(totalRev / 1000) + 'rb'}</div>
      <div class="kpi-lbl">Total Omzet</div>
      <div class="kpi-change ${revChange >= 0 ? 'up' : 'dn'}">${revChange >= 0 ? '↑' : '↓'} ${Math.abs(revChange)}% vs sebelumnya</div>
    </div>
    <div class="kpi-card k2">
      <div class="kpi-ic k2"><svg width="15" height="15"><use href="#I-check-circle"/></svg></div>
      <div class="kpi-val">${totalOrders}</div>
      <div class="kpi-lbl">Pesanan Selesai</div>
      <div class="kpi-change up">↑ ${Math.max(0, totalOrders - Math.round(totalOrders * 0.85))} vs sebelumnya</div>
    </div>
    <div class="kpi-card k3">
      <div class="kpi-ic k3"><svg width="15" height="15"><use href="#I-receipt"/></svg></div>
      <div class="kpi-val">Rp${Math.round(avgOrder / 1000)}rb</div>
      <div class="kpi-lbl">Rata-rata Order</div>
      <div class="kpi-change up">↑ 5% vs sebelumnya</div>
    </div>
    <div class="kpi-card k4">
      <div class="kpi-ic k4"><svg width="15" height="15"><use href="#I-wallet"/></svg></div>
      <div class="kpi-val">${topPay ? topPay[0] : 'COD'}</div>
      <div class="kpi-lbl">Pembayaran Terfavorit</div>
      <div class="kpi-change up">${topPay ? Math.round(topPay[1] / totalRev * 100) : 0}% dari total</div>
    </div>`;

      renderSalesChart(data);

      const cod = data.filter(o => o.pay.includes('COD')).reduce((s, o) => s + o.total, 0);
      const qris = data.filter(o => o.pay.includes('QRIS')).reduce((s, o) => s + o.total, 0);
      document.getElementById('sales-summary-box').innerHTML = `
    <div class="sb-title">🧾 RINGKASAN PEMBAYARAN</div>
    <div class="sb-row"><span>COD</span><span>Rp${(cod / 1000).toFixed(0)}k</span></div>
    <div class="sb-row"><span>QRIS</span><span>Rp${(qris / 1000).toFixed(0)}k</span></div>
    <div class="sb-row"><span>Total Pendapatan</span><span>Rp${(totalRev / 1000).toFixed(0)}k</span></div>`;


      const ranked = Object.entries(menuQty).sort((a, b) => b[1] - a[1]).slice(0, 5);
      const maxQty = ranked[0] ? ranked[0][1] : 1;
      const rankClass = ['r1', 'r2', 'r3', 'rn', 'rn'];
      document.getElementById('bestseller-list-content').innerHTML = ranked.map(([mid, qty], idx) => {
        const m = MENUS.find(x => x.id === parseInt(mid));
        if (!m) return '';
        const rev = data.reduce((s, o) => { const it = o.items.find(i => i.menuId === parseInt(mid)); return s + (it ? m.price * it.qty : 0); }, 0);
        return `<div class="bsitem">
      <div class="bsrank ${rankClass[idx]}">${idx + 1}</div>
      <div style="flex:1;min-width:0">
        <div class="bsname" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${m.name}</div>
        <div class="bsqty">${qty} terjual</div>
        <div class="bs-bar"><div class="bs-bar-fill" style="width:${Math.round(qty / maxQty * 100)}%"></div></div>
      </div>
      <div class="bsrev">Rp${(rev / 1000).toFixed(0)}k</div>
    </div>`;
      }).join('');

      document.getElementById('txn-list').innerHTML = data.map(o => {
        const itemStr = o.items.map(i => { const m = MENUS.find(x => x.id === i.menuId); return m ? `${m.name} x${i.qty}` : '' }).filter(Boolean).join(' · ');
        const payClass = o.pay.includes('COD') ? 'cod' : o.pay.includes('Transfer') ? 'tf' : 'qr';
        const payShort = o.pay.includes('COD') ? 'COD' : o.pay.includes('Transfer') ? 'TF' : 'QRIS';
        const dayStr = o.day === 0 ? 'Hari ini' : o.day === 1 ? 'Kemarin' : `${o.day} hari lalu`;
        return `<div class="txn-row" onclick="openSellerOrder('${o.id}')">
      <div class="txn-top">
        <div><div class="txn-id">#${o.id}</div><div class="txn-cust">${o.custName}</div></div>
        <div class="txn-time">${dayStr} ${o.time}</div>
      </div>
      <div class="txn-items">${itemStr}</div>
      <div class="txn-bot">
        <span class="txn-pay ${payClass}">${payShort}</span>
        <span class="txn-total">Rp${o.total.toLocaleString('id-ID')}</span>
      </div>
    </div>`;
      }).join('');

      document.getElementById('chart-period-lbl').textContent = currentSalesPeriod === 'today' ? 'Per Jam' : currentSalesPeriod === 'week' ? 'Per Hari (7 Hari)' : 'Per Hari (30 Hari)';

      // Render panel Smart Forecasting / BOM (REQ-F-043 s.d. REQ-F-046)
      renderBOM(data);
    }

    function renderSalesChart(data) {
      const container = document.getElementById('sales-bar-chart');
      let bars = [];
      if (currentSalesPeriod === 'today') {
        const hours = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17];
        bars = hours.map(h => ({ lbl: h + '', val: data.filter(o => o.hour === h).reduce((s, o) => s + o.total, 0) }));
      } else if (currentSalesPeriod === 'week') {
        const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        const today = new Date().getDay();
        bars = Array.from({ length: 7 }, (_, i) => 6 - i).reverse().map(daysAgo => {
          const dow = (today - daysAgo + 7) % 7;
          return { lbl: dayNames[dow], val: data.filter(o => o.day === daysAgo).reduce((s, o) => s + o.total, 0) };
        });
      } else {
        bars = Array.from({ length: 14 }, (_, i) => 13 - i).reverse().map(d => ({ lbl: d === 0 ? 'H' : d % 7 === 0 ? '-' + d : '', val: data.filter(o => o.day === d).reduce((s, o) => s + o.total, 0) }));
      }
      const maxVal = Math.max(...bars.map(b => b.val), 1);
      container.innerHTML = bars.map((b) => {
        const pct = Math.round(b.val / maxVal * 100);
        const isActive = b.val === maxVal && b.val > 0;
        const barColor = isActive ? 'var(--red)' : 'rgba(158,9,15,' + (0.15 + pct / 100 * 0.6) + ')';
        return `<div class="bar-col" title="Rp${b.val.toLocaleString('id-ID')}">
      <div class="bar-amt" style="color:${b.val > 0 ? 'var(--g6)' : 'transparent'}">${b.val >= 1000 ? Math.round(b.val / 1000) + 'k' : ''}</div>
      <div class="bar-fill ${isActive ? 'active' : ''}" style="height:${Math.max(pct, b.val > 0 ? 8 : 2)}%;background:${barColor}"></div>
      <div class="bar-lbl">${b.lbl || '·'}</div>
    </div>`;
      }).join('');
    }

    let confirmCb = null;
    function openConfirm(title, text, cb) {
      document.getElementById('confirm-title').textContent = title;
      document.getElementById('confirm-text').textContent = text;
      confirmCb = cb;
      document.getElementById('ovl-confirm').classList.add('show');
    }
    function closeConfirm() {
      document.getElementById('ovl-confirm').classList.remove('show');
    }
    document.getElementById('confirm-yes-btn').onclick = function () {
      if (confirmCb) confirmCb();
      closeConfirm();
    };

    function confirmLogout() {
      openConfirm("Keluar Akun", "Apakah kamu yakin ingin keluar dari panel penjual?", () => {
        localStorage.removeItem('userRole');
        window.location.href = '{{ route('welcome') }}';
      });
    }

    function confirmDeleteMenu(mid) {
      const m = MENUS.find(x => x.id === mid);
      openConfirm("Hapus Menu", `Apakah kamu yakin ingin menghapus "${m ? m.name : 'menu ini'}"?`, () => {
        MENUS = MENUS.filter(x => x.id !== mid);
        renderMenuTab();
      });
    }

    // ═══════════════════════════════════════════════════
    // SMART FORECASTING / BOM (REQ-F-043 s.d. REQ-F-046)
    // ═══════════════════════════════════════════════════

    // Data bahan baku (raw_materials) — simulasi data persisten
    const RAW_MATERIALS = [
      { id: 1, name: 'Daging Ayam Segar', unit: 'kg', current_stock: 8.5, minimum_threshold: 5.0 },
      { id: 2, name: 'Tepung Bumbu Crispy', unit: 'kg', current_stock: 3.2, minimum_threshold: 2.0 },
      { id: 3, name: 'Minyak Goreng', unit: 'liter', current_stock: 12.0, minimum_threshold: 5.0 },
      { id: 4, name: 'Nasi Putih (Beras)', unit: 'kg', current_stock: 15.0, minimum_threshold: 8.0 },
      { id: 5, name: 'Cabai Merah', unit: 'kg', current_stock: 1.2, minimum_threshold: 1.5 },
      { id: 6, name: 'Kentang', unit: 'kg', current_stock: 4.0, minimum_threshold: 2.0 },
      { id: 7, name: 'Teh Celup', unit: 'pcs', current_stock: 80, minimum_threshold: 30 },
      { id: 8, name: 'Gula Pasir', unit: 'kg', current_stock: 5.0, minimum_threshold: 2.0 },
    ];

    // Bill of Materials: quantity_needed per 1 porsi menu
    const MENU_BOM = [
      // Ayam Goreng Crispy (menuId:1)
      { product_id: 1, raw_material_id: 1, quantity_needed: 0.25 },
      { product_id: 1, raw_material_id: 2, quantity_needed: 0.05 },
      { product_id: 1, raw_material_id: 3, quantity_needed: 0.1 },
      // Paket Nasi Ayam (menuId:2)
      { product_id: 2, raw_material_id: 1, quantity_needed: 0.25 },
      { product_id: 2, raw_material_id: 2, quantity_needed: 0.05 },
      { product_id: 2, raw_material_id: 3, quantity_needed: 0.1 },
      { product_id: 2, raw_material_id: 4, quantity_needed: 0.15 },
      // Kentang Goreng (menuId:3)
      { product_id: 3, raw_material_id: 6, quantity_needed: 0.15 },
      { product_id: 3, raw_material_id: 3, quantity_needed: 0.08 },
      // Es Teh Manis (menuId:4)
      { product_id: 4, raw_material_id: 7, quantity_needed: 1 },
      { product_id: 4, raw_material_id: 8, quantity_needed: 0.02 },
      // Ayam Pedas Level 3 (menuId:5)
      { product_id: 5, raw_material_id: 1, quantity_needed: 0.25 },
      { product_id: 5, raw_material_id: 2, quantity_needed: 0.05 },
      { product_id: 5, raw_material_id: 3, quantity_needed: 0.1 },
      { product_id: 5, raw_material_id: 5, quantity_needed: 0.03 },
      // Paket Hemat Duo (menuId:6)
      { product_id: 6, raw_material_id: 1, quantity_needed: 0.5 },
      { product_id: 6, raw_material_id: 2, quantity_needed: 0.1 },
      { product_id: 6, raw_material_id: 3, quantity_needed: 0.2 },
      { product_id: 6, raw_material_id: 4, quantity_needed: 0.3 },
      // Teh Manis Hangat (menuId:7)
      { product_id: 7, raw_material_id: 7, quantity_needed: 1 },
      { product_id: 7, raw_material_id: 8, quantity_needed: 0.02 },
      // Cireng Bumbu (menuId:8)
      { product_id: 8, raw_material_id: 2, quantity_needed: 0.04 },
      { product_id: 8, raw_material_id: 3, quantity_needed: 0.06 },
    ];

    function renderBOM(salesData) {
      // Hitung qty terjual per menu dalam periode
      const menuQtySold = {};
      salesData.forEach(o => o.items.forEach(i => {
        menuQtySold[i.menuId] = (menuQtySold[i.menuId] || 0) + i.qty;
      }));

      // Langkah 1: estimasi_kebutuhan = Σ (qty_terjual × quantity_needed_per_porsi) per bahan baku
      const estimasiKebutuhan = {};
      MENU_BOM.forEach(bom => {
        const qtySold = menuQtySold[bom.product_id] || 0;
        estimasiKebutuhan[bom.raw_material_id] = (estimasiKebutuhan[bom.raw_material_id] || 0) + (qtySold * bom.quantity_needed);
      });

      // Langkah 2: tentukan label status logistik
      const bomList = document.getElementById('bom-list');
      const items = RAW_MATERIALS.map(rm => {
        const estimasi = estimasiKebutuhan[rm.id] || 0;
        let label, labelColor, labelBg;
        if (rm.current_stock < rm.minimum_threshold) {
          label = 'Restock Segera';
          labelColor = '#C0392B'; labelBg = 'rgba(192,57,43,.12)';
        } else if (rm.current_stock < estimasi * 1.2) {
          label = 'Prioritas Tinggi';
          labelColor = '#B8820A'; labelBg = 'rgba(255,178,30,.18)';
        } else {
          label = 'Stok Aman';
          labelColor = '#1E9E52'; labelBg = 'rgba(39,174,96,.12)';
        }
        const rekomendasi = Math.max(0, Math.ceil((estimasi * 1.2) - rm.current_stock));
        return { rm, estimasi, label, labelColor, labelBg, rekomendasi };
      });

      bomList.innerHTML = items.map(it => `
        <div style="display:flex;align-items:center;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--g2)">
          <div style="flex:1;min-width:0">
            <div style="font-size:12px;font-weight:700;color:var(--bk);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${it.rm.name}</div>
            <div style="font-size:10px;color:var(--g4);margin-top:2px">Stok: ${it.rm.current_stock} ${it.rm.unit} · Est. kebutuhan: ${it.estimasi.toFixed(1)} ${it.rm.unit}${it.rekomendasi > 0 ? ' · <b style="color:var(--red)">Beli: ' + it.rekomendasi + ' ' + it.rm.unit + '</b>' : ''}</div>
          </div>
          <span style="margin-left:10px;padding:3px 9px;border-radius:999px;font-size:10px;font-weight:700;background:${it.labelBg};color:${it.labelColor};flex-shrink:0">${it.label}</span>
        </div>`).join('');
    }

    // Init
    renderSeller();

  </script>
</body>

</html>