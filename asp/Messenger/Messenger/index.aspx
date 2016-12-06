<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="index.aspx.cs" Inherits="Messenger.index" MasterPageFile="~/layout.Master" %>
<asp:Content ID="indexTitle" ContentPlaceHolderID="title" runat="server">
    <link rel="stylesheet" type="text/css" href="public/dist/myStyle.css" />
     <!-- links to rescoures -->
    <link rel="icon", type="image/x-icon", href="public/images/favicon.ico" />
    <link rel="shortcut icon", type="image/x-icon", href="public/images/favicon.ico" />
    <title>Home</title>
</asp:Content>

<asp:Content ID="index" ContentPlaceHolderID="body" runat="server">
   <div class="card">
      <div class="card-block">
         <div class="media">
            <a class="media-left" href="#">
            <img class="card-img-top" src="public/images/messagerLog.png" alt="Card image cap">
            </a>
            <div class="media-body">
               <h1 class="media-heading">Welcome</h1>
               Stay connect with one another.
            </div>
         </div>
      </div>
      <ul class="list-group list-group-flush">
         <li class="list-group-item">
            <a class="btn btn-primary" data-toggle="collapse" href="#login" aria-expanded="false" aria-controls="login">
            Login
            </a>
         </li>
      </ul>
      <div class="collapse" id="login">
         <div class="card card-block">
            <form  id="loginForm" runat="server">
               <div class="form-group row">
                  <label for="fName" class="col-xs-2 col-form-label">First Name</label>
                  <div class="col-xs-10">
                     <asp:TextBox ID="txtFName" runat="server" CssClass="form-control"></asp:TextBox>
                  </div>
               </div>
               <div class="form-group row">
                  <label for="fName" class="col-xs-2 col-form-label">Last Name</label>
                  <div class="col-xs-10">
                     <asp:TextBox ID="txtLName"  runat="server" CssClass="form-control"></asp:TextBox>
                  </div>
               </div>
               <div class="form-group row">
                  <label for="fName" class="col-xs-2 col-form-label">Birthday Day</label>
                  <div class="col-xs-10">
                     <asp:TextBox ID="txtBDay" runat="server" CssClass="form-control bDay"></asp:TextBox>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="offset-sm-2 col-xs-10">
                     <asp:Button ID="btnLogin" runat="server" Text="Sumbit" CssClass="btn btn-primary" OnClick="btnLogin_Click" />
                  </div>
               </div>
                <asp:SqlDataSource ID="loginPerson" runat="server" ConnectionString='<%$ ConnectionStrings:Messenger2 %>' ProviderName='<%$ ConnectionStrings:Messenger2.ProviderName %>' SelectCommand="SELECT * FROM [person] WHERE (([fName] = ?) AND ([lName] = ?) AND ([birthday] = ?))" InsertCommand="INSERT INTO [person] ([fName], [lName], [birthday]) VALUES (?, ?, ?)">
                    <InsertParameters>
                        <asp:ControlParameter ControlID="txtFName" PropertyName="Text" Name="fName" Type="String"></asp:ControlParameter>
                        <asp:ControlParameter ControlID="txtLName" PropertyName="Text" Name="lName" Type="String"></asp:ControlParameter>
                        <asp:ControlParameter ControlID="txtBDay" PropertyName="Text" Name="birthday" Type="DateTime"></asp:ControlParameter>
                    </InsertParameters>
                    <SelectParameters>
                        <asp:ControlParameter ControlID="txtFName" PropertyName="Text" Name="fName" Type="String"></asp:ControlParameter>
                        <asp:ControlParameter ControlID="txtLName" PropertyName="Text" Name="lName" Type="String"></asp:ControlParameter>
                        <asp:ControlParameter ControlID="txtBDay" PropertyName="Text" Name="birthday" Type="DateTime"></asp:ControlParameter>
                    </SelectParameters>
                </asp:SqlDataSource>
            </form>
         </div>
      </div>
   </div>

    <script src="public/dist/my-com.js" type="text/javascript"></script>
</asp:Content>