//+------------------------------------------------------------------+
//|                                                           si.mq5 |
//|                                    Copyright 2023, Anton Sander  |
//|                                          https://sanderinvest.com|
//+------------------------------------------------------------------+
#property copyright "Copyright 2023, Anton Sander"
#property link      "https://sanderinvest.com"
#property version   "2.01"
//--- inputs
  input string  prefix   = "https://";
  input string  password = "";
  input int     timeout  = 500;
  input int     refresh  = 60;
  input bool    debug    = false;
//--- vars
  string  account, path;
  string url    = "sanderinvest.com";
  string script = "/webscripts/01_status.php";
  ushort separator=StringGetCharacter(",",0);//--- Get the separator code
  string cmd, symbol;
  int          digits;
  double       point;
//+------------------------------------------------------------------+
//| Initialize expert                                                |
//+------------------------------------------------------------------+
void OnInit()
  {
  //--- default value for symbol and period
  account = IntegerToString(AccountInfoInteger(ACCOUNT_LOGIN));
  path = prefix + url + script;
  Comment("Please activate: Tools->Options->Expert Advisor\n"+
  "   1.Allow automated Trading\n"+
  "   2.Allow WebRequest\n"+
  "   3.add new URL:  "+prefix + url +"\n");
  //---
  ChartRedraw();
  EventSetTimer(refresh);
  }
void OnDeinit(const int reason){EventKillTimer();Comment("");}
void OnTick()
  {return;}
//+------------------------------------------------------------------+
//| Process chart events                                             |
//+------------------------------------------------------------------+
void OnTimer()
  {
  string command[];
  string result;
  result = MyWebRequest("START");
  while(true)
    {
    if(isRequestDone(result)) return;
    int k=StringSplit(result,separator,command);
    if     (k > 1 && command[1] == "POSITIONS") result = sendOpenedPositions();
    else if(k > 2 && command[1] == "DEAL")      result = sendClosedDeal(command[2]);
    else if(k > 2 && command[1] == "EQUITY")    result = getEquity(command[2]);
    else if(k > 2 && command[1] == "UPDATE")    result = sendUpdatePositions(command[2]);
    else result= "OK: command not found";
    }
  }//OnTimer
bool isRequestDone(string res)
  {// done, show servertime and response from database
  if(debug) Print("isRequestDone: "+res);
  if(StringSubstr(res,0,3) == "GET") return false;
  Comment(TimeToString(TimeCurrent(),TIME_MINUTES|TIME_SECONDS) + " : " + res); //CommentXY(kopf,600);
  return true;
  }
string sendOpenedPositions() 
  {
    string itime;
  string kopf  = "POSITIONS\nticket,type,symbol,lots,open_time,open_price,profit,swap";
  int    total = PositionsTotal();
  for(int pos=0;pos<total;pos++)
    {
    ulong ticket = PositionGetTicket(pos);
    if(!PositionSelectByTicket(ticket)) continue;
    itime = TimeToString(PositionGetInteger(POSITION_TIME));
    StringReplace(itime,".","-");
    kopf += "\n"+ IntegerToString(ticket);
    kopf += ";" + IntegerToString(PositionGetInteger(POSITION_TYPE));
    kopf += ";" + PositionGetString(POSITION_SYMBOL);
    kopf += ";" + DoubleToString(PositionGetDouble(POSITION_VOLUME),2);
    kopf += ";" + itime;
    kopf += ";" + DoubleToString(PositionGetDouble(POSITION_PRICE_OPEN),5);
    kopf += ";" + DoubleToString(PositionGetDouble(POSITION_PROFIT),2);
    kopf += ";" + DoubleToString(PositionGetDouble(POSITION_SWAP),2);
    }
  return MyWebRequest(kopf);
  }
string sendClosedDeal(string pid)
  { 
  ulong ticket;
  string itime;
  string kopf = "DEAL\nticket,close_time,close_price,profit,swap";
  if(HistorySelectByPosition(StringToInteger(pid)))
    {
    int total  = HistoryDealsTotal();
    for(int pos=0;pos<total;pos++)
      {
      if((ticket=HistoryDealGetTicket(pos))>0 )
        {
        if(HistoryDealGetInteger(ticket,DEAL_ENTRY) == 1)//DEAL_ENTRY_OUT
          {
          itime = TimeToString(HistoryDealGetInteger(ticket,DEAL_TIME));
          StringReplace(itime,".","-");
          kopf += "\n"+ pid;
          kopf += ";" + itime;
          kopf += ";" +              DoubleToString(HistoryDealGetDouble(ticket,DEAL_PRICE),5);
          kopf += ";" +              DoubleToString(HistoryDealGetDouble(ticket,DEAL_PROFIT),2);
          kopf += ";" +              DoubleToString(HistoryDealGetDouble(ticket,DEAL_SWAP),2);
          }//if deal_entry
        }//if ticket
      }// for pos
      //kopf = pid;  
    }
  else kopf = "client: position not found, ticket: "+pid;
  return MyWebRequest(kopf);
  }
string sendUpdatePositions(string date)
  {
  ulong ticket,pid;
  string itime;
  string kopf = "UPDATE\nticket,type,symbol,open_time,lots";
  HistorySelect(StringToTime(date),TimeCurrent());
  int total = HistoryDealsTotal();
  for(int pos=0;pos<total;pos++){
    if((ticket=HistoryDealGetTicket(pos))>0 ){
      if((pid = HistoryDealGetInteger(ticket,DEAL_POSITION_ID))>0){
      if((HistoryDealGetInteger(ticket,DEAL_ENTRY) == DEAL_ENTRY_IN) && (HistoryDealGetInteger(ticket,DEAL_TYPE) < 2))
          {
          itime = TimeToString(HistoryDealGetInteger(ticket,DEAL_TIME));
          StringReplace(itime,".","-");
          kopf += "\n" +IntegerToString(pid);
          kopf += ";" + IntegerToString(HistoryDealGetInteger(ticket,DEAL_TYPE));
          kopf += ";" + HistoryDealGetString(ticket,DEAL_SYMBOL);
          kopf += ";" + itime;
          kopf += ";" + DoubleToString(HistoryDealGetDouble(ticket,DEAL_VOLUME),2);
          }//if History
      }//if pid
    }//if ticket
  }//for
  return MyWebRequest(kopf);
  }
string MyWebRequest(string req)
  // extern: account,password,url,timeout
  {
  int ires;
  char   data[];           // the array of the HTTP message body
  char result[];
  string res,result_headers,headers;
  if(debug) Print(req+"\n-------");
  StringToCharArray(account+";"+password+";"+req,data,0,WHOLE_ARRAY,CP_UTF8);
  ArrayResize(data,ArraySize(data)-1);
  ResetLastError();
  //string stime = TimeToString(TimeCurrent()); //ires  = StringReplace(stime,".","-");
  ires = WebRequest("POST",path,headers,timeout,data,result,result_headers);
  res = CharArrayToString(result);
  if(ires != 200)
    {
    int err = GetLastError();
    string serr;
    if(err == 4060) serr="Please Allow WebRequest.  Tools->Options->Expert Advisor\nand set for listed URL: " +url;
    if(err == 4014) serr= "URL not found: " + url;
    else serr="Error connect: "+IntegerToString(err);
    if(debug) Print("MyWebRequest: ires: "+IntegerToString(ires)+", err: "+IntegerToString(err));
    return serr;
    }
  //res = CharArrayToString(result);
  return res;
    //
  }
string getEquity(string equity)
  {
  string kopf = "OK\nnew size of lot calculated by equity: " + equity;
  return MyWebRequest(kopf);
  }
